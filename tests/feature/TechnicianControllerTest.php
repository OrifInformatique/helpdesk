<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Helpdesk\Models\Roles_model;
use Helpdesk\Models\User_data_model;

/**
 * Tests pour le contrôleur Technician
 * 
 * @internal
 */
final class TechnicianControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $roles_model;
    protected $user_data_model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roles_model = new Roles_model();
        $this->user_data_model = new User_data_model();
    }

    /**
     * Test du dashboard avec fk_role_id valide
     */
    public function testDashboardWithValidFkRoleId()
    {
        // Créer un rôle de test
        $roleData = [
            'name_role' => 'Technicien Test',
            'priority_role' => 5
        ];
        $roleId = $this->roles_model->insert($roleData);
        $insertedRoleId = $this->roles_model->insertID();

        // Créer un utilisateur de test avec fk_role_id
        // Note: Cette partie nécessite que la table user existe
        // Vous devrez peut-être adapter selon votre structure de base de données
        $db = \Config\Database::connect();
        
        // Insérer un utilisateur de test
        $userData = [
            'username' => 'testuser',
            'password' => password_hash('testpass', PASSWORD_DEFAULT),
            'fk_user_type' => 2
        ];
        $db->table('user')->insert($userData);
        $userId = $db->insertID();

        // Insérer les données utilisateur avec fk_role_id
        $userDataModel = [
            'fk_user_id' => $userId,
            'fk_role_id' => $insertedRoleId,
            'last_name_user_data' => 'Doe',
            'first_name_user_data' => 'John'
        ];
        $this->user_data_model->insert($userDataModel);

        $session = [
            'logged_in' => true,
            'user_id' => $userId,
            'user_type' => 2
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/technician/dashboard/' . $userId);

        $result->assertStatus(200);
        $result->assertSee('Technicien Test', 'body');
    }

    /**
     * Test du dashboard avec fk_role_id invalide (fallback getUserRole)
     */
    public function testDashboardWithInvalidFkRoleIdFallsBackToGetUserRole()
    {
        // Créer un utilisateur sans fk_role_id valide
        $db = \Config\Database::connect();
        
        $userData = [
            'username' => 'testuser2',
            'password' => password_hash('testpass', PASSWORD_DEFAULT),
            'fk_user_type' => 2
        ];
        $db->table('user')->insert($userData);
        $userId = $db->insertID();

        // Insérer les données utilisateur sans fk_role_id ou avec fk_role_id invalide
        $userDataModel = [
            'fk_user_id' => $userId,
            'fk_role_id' => null, // ou 99999 pour un ID invalide
            'last_name_user_data' => 'Smith',
            'first_name_user_data' => 'Jane'
        ];
        $this->user_data_model->insert($userDataModel);

        $session = [
            'logged_in' => true,
            'user_id' => $userId,
            'user_type' => 2
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/technician/dashboard/' . $userId);

        // Le dashboard doit quand même s'afficher, utilisant le fallback
        $result->assertStatus(200);
    }

    /**
     * Test du dashboard sans rôle (fallback ancien système)
     */
    public function testDashboardWithoutRoleFallsBackToOldSystem()
    {
        // Créer un utilisateur avec fk_user_type mais sans rôle
        $db = \Config\Database::connect();
        
        $userData = [
            'username' => 'testuser3',
            'password' => password_hash('testpass', PASSWORD_DEFAULT),
            'fk_user_type' => 1 // Admin
        ];
        $db->table('user')->insert($userData);
        $userId = $db->insertID();

        // Insérer les données utilisateur sans fk_role_id
        $userDataModel = [
            'fk_user_id' => $userId,
            'fk_role_id' => null,
            'last_name_user_data' => 'Admin',
            'first_name_user_data' => 'User'
        ];
        $this->user_data_model->insert($userDataModel);

        $session = [
            'logged_in' => true,
            'user_id' => $userId,
            'user_type' => 1
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/technician/dashboard/' . $userId);

        // Le dashboard doit s'afficher avec le rôle déterminé via fk_user_type
        $result->assertStatus(200);
        // Vérifier que le rôle admin est affiché (via la traduction)
        // Note: Vous devrez peut-être adapter selon vos traductions
    }

    /**
     * Test du dashboard avec différents types d'utilisateurs (fallback ancien système)
     */
    public function testDashboardWithDifferentUserTypes()
    {
        $db = \Config\Database::connect();
        $userTypes = [
            1 => 'admin',
            2 => 'user',
            3 => 'guest',
            4 => 'mentor'
        ];

        foreach ($userTypes as $typeId => $typeName) {
            $userData = [
                'username' => 'testuser_' . $typeName,
                'password' => password_hash('testpass', PASSWORD_DEFAULT),
                'fk_user_type' => $typeId
            ];
            $db->table('user')->insert($userData);
            $userId = $db->insertID();

            $userDataModel = [
                'fk_user_id' => $userId,
                'fk_role_id' => null,
                'last_name_user_data' => ucfirst($typeName),
                'first_name_user_data' => 'Test'
            ];
            $this->user_data_model->insert($userDataModel);

            $session = [
                'logged_in' => true,
                'user_id' => $userId,
                'user_type' => $typeId
            ];

            $result = $this->withSession($session)
                ->get('/helpdesk/technician/dashboard/' . $userId);

            $result->assertStatus(200);
        }
    }

    /**
     * Test du dashboard avec présence
     */
    public function testDashboardWithPresence()
    {
        // Créer un rôle
        $roleData = [
            'name_role' => 'Test Role',
            'priority_role' => 1
        ];
        $this->roles_model->insert($roleData);
        $roleId = $this->roles_model->insertID();

        // Créer un utilisateur
        $db = \Config\Database::connect();
        $userData = [
            'username' => 'testuser_presence',
            'password' => password_hash('testpass', PASSWORD_DEFAULT),
            'fk_user_type' => 2
        ];
        $db->table('user')->insert($userData);
        $userId = $db->insertID();

        $userDataModel = [
            'fk_user_id' => $userId,
            'fk_role_id' => $roleId,
            'last_name_user_data' => 'Presence',
            'first_name_user_data' => 'Test'
        ];
        $this->user_data_model->insert($userDataModel);

        // Créer une présence (si la table existe)
        // Note: Adaptez selon votre structure
        if ($db->tableExists('tbl_presences')) {
            $presenceData = [
                'fk_user_id' => $userId,
                'date_presence' => date('Y-m-d')
            ];
            $db->table('tbl_presences')->insert($presenceData);
        }

        $session = [
            'logged_in' => true,
            'user_id' => $userId,
            'user_type' => 2
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/technician/dashboard/' . $userId);

        $result->assertStatus(200);
    }
}

