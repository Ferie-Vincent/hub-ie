<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\BadgeStatus;
use App\Enums\EnrollmentStatus;
use App\Models\Application;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Edition;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopCourseFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DemoParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $edition = Edition::current() ?? Edition::first();
        $admin = User::role('super_admin')->first();

        // ── 1. Utilisateur participant demo ─────────────────────────────────
        $participant = User::firstOrCreate(
            ['email' => 'participant@demo.ci'],
            [
                'first_name' => 'Adjoua',
                'last_name' => 'KONAN',
                'password' => Hash::make('password'),
                'phone' => '+2250700123456',
                'nationality' => 'Ivoirienne',
                'city' => 'Abidjan',
                'gender' => 'F',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $participant->assignRole('candidate');

        // ── 2. Ateliers (2 premiers disponibles) ────────────────────────────
        // Un candidat ne participe qu'à 1 seul atelier
        $workshops = Workshop::where('is_published', true)
            ->orderBy('display_order')
            ->limit(1)
            ->get();

        if ($workshops->isEmpty()) {
            $this->command->warn('Aucun atelier publié — workshops non rattachés.');
        }

        // ── 3. Application acceptée ─────────────────────────────────────────
        $application = Application::firstOrCreate(
            ['user_id' => $participant->id],
            [
                'edition_id' => $edition?->id,
                'reference_code' => 'HIE2026-DEMO01',
                'status' => ApplicationStatus::Accepted->value,
                'current_step' => 4,
                'category' => 'pme_exportatrice',
                'organization_name' => 'KONAN Import-Export SARL',
                'organization_type' => 'Société commerciale',
                'position' => 'Directrice Générale',
                'sector' => 'Agroalimentaire',
                'experience_years' => 8,
                'rccm_number' => 'CI-ABJ-2019-B-12345',
                'professional_email' => 'dg@konan-import-export.ci',
                'professional_phone' => '+2250720456789',
                'motivation' => "Je dirige une PME agroalimentaire spécialisée dans l'exportation de noix de cajou transformées vers les marchés européens et asiatiques. La participation au Hub Import-Export 2026 me permettra de consolider mes connaissances en réglementation douanière et d'accéder aux opportunités de financement offertes par les institutions partenaires. Je souhaite notamment renforcer ma capacité à négocier avec les acheteurs internationaux.",
                'chosen_workshops' => $workshops->take(1)->pluck('id')->toArray(),
                'expectations' => 'Renforcer mon réseau, comprendre les mécanismes de financement export, accéder aux marchés CEDEAO.',
                'referral_source' => 'Ministère du Commerce',
                'is_first_participation' => true,
                'rgpd_consent' => true,
                'communication_consent' => true,
                'submitted_at' => now()->subDays(45),
                'accepted_at' => now()->subDays(20),
                'notified_at' => now()->subDays(20),
                'group_label' => 'G1',
                'qr_token' => Str::random(48),
                'check_in_code' => '847291',
                'average_score' => 16.50,
                'admin_notes' => 'Dossier exemplaire. Profil exportateur confirmé. Priorité G1.',
            ]
        );

        // Rattachement — 1 seul atelier par candidat
        if ($workshops->isNotEmpty()) {
            $application->workshops()->sync([$workshops->first()->id]);
        }

        // ── 4b. Enrollment (pour le système de scan / pointage) ─────────────
        $enrollment = null;
        if ($workshops->isNotEmpty()) {
            $enrollment = Enrollment::firstOrCreate(
                ['user_id' => $participant->id],
                [
                    'workshop_id' => $workshops->first()->id,
                    'status' => EnrollmentStatus::Enrolled,
                    'badge_status' => BadgeStatus::Valid,
                    'qr_token' => $application->qr_token,
                    'check_in_code' => (int) $application->check_in_code,
                    'cancellation_token' => Str::random(64),
                    'enrolled_at' => now()->subDays(20),
                ]
            );
        }

        // ── 4. Fichiers de cours par atelier ────────────────────────────────
        $courseFilesData = [
            [
                'title' => 'Introduction au commerce international',
                'description' => 'Fondamentaux du commerce international : Incoterms, moyens de paiement, douane.',
                'original_filename' => 'intro-commerce-international.pdf',
                'mime_type' => 'application/pdf',
                'file_size_bytes' => 2_457_600, // 2.3 Mo
                'sort_order' => 1,
            ],
            [
                'title' => 'Réglementation douanière CEDEAO 2026',
                'description' => 'Mise à jour des règles douanières et tarifs communs dans l\'espace CEDEAO.',
                'original_filename' => 'reglementation-douaniere-cedeao-2026.pdf',
                'mime_type' => 'application/pdf',
                'file_size_bytes' => 5_242_880, // 5 Mo
                'sort_order' => 2,
            ],
            [
                'title' => 'Présentation atelier — Jour 1',
                'description' => 'Slides de la session d\'ouverture et ateliers du premier jour.',
                'original_filename' => 'presentation-j1.pptx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'file_size_bytes' => 8_388_608, // 8 Mo
                'sort_order' => 3,
            ],
            [
                'title' => 'Guide des financements export — BNI & SFI',
                'description' => 'Mécanismes de financement disponibles pour les exportateurs ivoiriens.',
                'original_filename' => 'guide-financements-export.pdf',
                'mime_type' => 'application/pdf',
                'file_size_bytes' => 1_572_864, // 1.5 Mo
                'sort_order' => 4,
                'created_at' => now()->subDays(2), // Récent → badge "Nouveau"
            ],
        ];

        foreach ($workshops as $idx => $workshop) {
            // 2 fichiers par atelier (on répartit les 4)
            $filePairs = array_chunk($courseFilesData, 2);
            $files = $filePairs[$idx] ?? $filePairs[0];

            foreach ($files as $fileData) {
                $fakePath = 'workshop-courses/demo-'.Str::slug($fileData['original_filename']).'.pdf';

                // Crée le fichier fake en storage pour que les downloads fonctionnent
                if (! Storage::disk('public')->exists($fakePath)) {
                    Storage::disk('public')->put(
                        $fakePath,
                        '%PDF-1.4 Demo file — Hub Import-Export 2026'
                    );
                }

                WorkshopCourseFile::firstOrCreate(
                    [
                        'workshop_id' => $workshop->id,
                        'title' => $fileData['title'],
                    ],
                    array_merge($fileData, [
                        'workshop_id' => $workshop->id,
                        'uploaded_by' => $admin?->id ?? 1,
                        'file_path' => $fakePath,
                        'is_published' => true,
                        'created_at' => $fileData['created_at'] ?? now()->subDays(10),
                    ])
                );
            }
        }

        // ── 5. Conversation avec formateur ──────────────────────────────────
        $firstWorkshop = $workshops->first();

        if ($firstWorkshop && $admin) {
            $conversation = Conversation::firstOrCreate(
                [
                    'application_id' => $application->id,
                    'workshop_id' => $firstWorkshop->id,
                ],
                [
                    'participant_user_id' => $participant->id,
                    'trainer_user_id' => $admin->id,
                    'subject' => 'Questions sur l\'atelier — '.$firstWorkshop->title,
                    'is_closed' => false,
                    'last_message_at' => now()->subMinutes(30),
                ]
            );

            $messages = [
                [
                    'sender_id' => $participant->id,
                    'body' => "Bonjour,\n\nJe viens de recevoir ma confirmation de participation et je suis très enthousiaste à l'idée de rejoindre le Hub Import-Export 2026.\n\nJ'ai une question concernant l'atelier : est-ce qu'il y aura des exercices pratiques sur les documents d'exportation (certificats d'origine, connaissements) ? Je souhaite préparer des cas concrets tirés de mon activité.\n\nMerci d'avance pour votre retour.\n\nAdjoua KONAN",
                    'read_at' => now()->subDays(3),
                    'created_at' => now()->subDays(4),
                ],
                [
                    'sender_id' => $admin->id,
                    'body' => "Bonjour Madame KONAN,\n\nMerci pour votre message et bienvenue parmi nous !\n\nOui, l'atelier prévoit une session pratique dédiée aux documents d'exportation. Nous travaillerons notamment sur les certificats d'origine CEDEAO, les connaissements maritimes et les crédits documentaires.\n\nJe vous encourage à apporter des exemples de votre activité — cela enrichira les échanges pour l'ensemble du groupe.\n\nJ'ai déposé un guide de préparation dans l'espace documents. N'hésitez pas à le consulter avant la session.\n\nCordialement,\nL'équipe Hub Import-Export",
                    'read_at' => now()->subDays(2),
                    'created_at' => now()->subDays(3),
                ],
                [
                    'sender_id' => $participant->id,
                    'body' => "Merci beaucoup pour cette réponse rapide et détaillée ! J'ai bien consulté le guide de préparation — il est très complet.\n\nJ'ai une dernière question : y a-t-il une liste de lecture ou de ressources recommandées pour approfondir la réglementation douanière CEDEAO avant la session ?\n\nBonne journée.",
                    'read_at' => null, // Non lu par le formateur
                    'created_at' => now()->subHours(2),
                ],
            ];

            foreach ($messages as $msgData) {
                ConversationMessage::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $msgData['sender_id'],
                    'body' => $msgData['body'],
                    'read_at' => $msgData['read_at'],
                    'created_at' => $msgData['created_at'],
                    'updated_at' => $msgData['created_at'],
                ]);
            }

            $conversation->update(['last_message_at' => now()->subHours(2)]);
        }

        // ── 6. Notifications database ────────────────────────────────────────
        $participant->notifications()->create([
            'id' => Str::uuid(),
            'type' => 'App\Notifications\NewCourseFileUploaded',
            'notifiable_type' => User::class,
            'notifiable_id' => $participant->id,
            'data' => json_encode([
                'type' => 'new_course_file',
                'file_title' => 'Guide des financements export — BNI & SFI',
                'workshop_title' => $firstWorkshop?->title ?? 'Atelier',
            ]),
            'read_at' => null,
            'created_at' => now()->subDays(2),
        ]);

        $participant->notifications()->create([
            'id' => Str::uuid(),
            'type' => 'App\Notifications\NewMessageReceived',
            'notifiable_type' => User::class,
            'notifiable_id' => $participant->id,
            'data' => json_encode([
                'type' => 'new_message',
                'sender_name' => $admin?->name ?? 'Équipe Hub IE',
                'preview' => 'Oui, l\'atelier prévoit une session pratique dédiée aux documents d\'exportation...',
            ]),
            'read_at' => now()->subDays(2),
            'created_at' => now()->subDays(3),
        ]);

        $this->command->info('✓ Participant demo : participant@demo.ci / password');
        $this->command->info('  Application : HIE2026-DEMO01 · Code : 847291 · Groupe G1');
        $this->command->info('  Enrollment ID : '.($enrollment?->id ?? 'n/a').' · statut : enrolled · badge : valid');
        $this->command->info('  '.($workshops->count()).' atelier(s) · '.($workshops->count() * 2).' fichiers · 1 conversation · 3 messages · 2 notifs');
    }
}
