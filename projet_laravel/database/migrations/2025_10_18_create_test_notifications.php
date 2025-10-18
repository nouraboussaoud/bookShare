<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Notification;
use App\Models\User;

return new class extends Migration
{
    public function up()
    {
        // Créer quelques notifications de test pour tester les boutons
        $users = User::all();
        
        if ($users->count() > 0) {
            $user = $users->first();
            
            // Créer des notifications de test
            Notification::create([
                'user_id' => $user->id,
                'type' => 'exchange_request',
                'title' => 'Test - Nouvelle demande d\'échange',
                'message' => 'Ceci est une notification de test pour vérifier les boutons.',
                'data' => [
                    'exchange_id' => 1,
                    'test' => true
                ],
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $user->id,
                'type' => 'exchange_status_change',
                'title' => 'Test - Échange accepté',
                'message' => 'Ceci est une autre notification de test.',
                'data' => [
                    'exchange_id' => 2,
                    'test' => true
                ],
                'is_read' => false,
            ]);
        }
    }

    public function down()
    {
        // Supprimer les notifications de test
        Notification::where('data->test', true)->delete();
    }
};