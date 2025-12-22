<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Helpdesk\Models\Roles_model;

/**
 * Tests pour le contrôleur Role
 * 
 * @internal
 */
final class RoleControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $roles_model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roles_model = new Roles_model();
    }

    /**
     * Test de l'affichage de la liste des rôles
     */
    public function testListRoleDisplaysCorrectly()
    {
        // Créer un rôle de test
        $roleData = [
            'name_role' => 'Test Role',
            'priority_role' => 1,
            'min_assignation_first_technician_role' => 0,
            'max_assignation_first_technician_role' => 10
        ];
        $this->roles_model->insert($roleData);

        // Simuler une session admin (nécessaire pour accéder au contrôleur)
        $session = [
            'logged_in' => true,
            'user_id' => 1,
            'user_type' => 1 // Admin
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/role/listRole');

        $result->assertStatus(200);
        $result->assertSee('Test Role', 'body');
    }

    /**
     * Test de l'affichage du formulaire de création de rôle
     */
    public function testSaveRoleFormDisplayForNewRole()
    {
        $session = [
            'logged_in' => true,
            'user_id' => 1,
            'user_type' => 1
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/role/saveRole/0');

        $result->assertStatus(200);
        // Vérifier que le formulaire contient les champs nécessaires
        $result->assertSee('name_role', 'body');
        $result->assertSee('priority_role', 'body');
    }

    /**
     * Test de la création d'un nouveau rôle avec succès
     */
    public function testSaveRoleCreatesNewRoleSuccessfully()
    {
        $session = [
            'logged_in' => true,
            'user_id' => 1,
            'user_type' => 1
        ];

        $postData = [
            'name_role' => 'Nouveau Rôle',
            'priority_role' => 5,
            'min_assignation_first_technician_role' => 2,
            'max_assignation_first_technician_role' => 8,
            'min_assignation_second_technician_role' => 1,
            'max_assignation_second_technician_role' => 5,
            'min_assignation_third_technician_role' => 0,
            'max_assignation_third_technician_role' => 3
        ];

        $result = $this->withSession($session)
            ->post('/helpdesk/role/saveRole/0', $postData);

        // Vérifier la redirection
        $result->assertRedirect();
        $result->assertRedirectTo('/helpdesk/role/listRole');

        // Vérifier que le rôle a été créé en base
        $role = $this->roles_model->where('name_role', 'Nouveau Rôle')->first();
        $this->assertNotNull($role);
        $this->assertEquals(5, $role['priority_role']);
    }

    /**
     * Test de la création d'un rôle avec validation échouée
     */
    public function testSaveRoleFailsValidation()
    {
        $session = [
            'logged_in' => true,
            'user_id' => 1,
            'user_type' => 1
        ];

        // Données invalides : nom vide et priorité invalide
        $postData = [
            'name_role' => '',
            'priority_role' => -1
        ];

        $result = $this->withSession($session)
            ->post('/helpdesk/role/saveRole/0', $postData);

        // Le formulaire doit être réaffiché avec les erreurs
        $result->assertStatus(200);
        // Les données doivent être conservées dans old_data
        $this->assertNotEmpty($result->getBody());
    }

    /**
     * Test de l'affichage du formulaire de modification de rôle
     */
    public function testSaveRoleFormDisplayForExistingRole()
    {
        // Créer un rôle de test
        $roleData = [
            'name_role' => 'Rôle à Modifier',
            'priority_role' => 3
        ];
        $roleId = $this->roles_model->insert($roleData);
        $insertId = $this->roles_model->insertID();

        $session = [
            'logged_in' => true,
            'user_id' => 1,
            'user_type' => 1
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/role/saveRole/' . $insertId);

        $result->assertStatus(200);
        $result->assertSee('Rôle à Modifier', 'body');
    }

    /**
     * Test de la modification d'un rôle existant
     */
    public function testSaveRoleUpdatesExistingRole()
    {
        // Créer un rôle de test
        $roleData = [
            'name_role' => 'Rôle Original',
            'priority_role' => 2
        ];
        $roleId = $this->roles_model->insert($roleData);
        $insertId = $this->roles_model->insertID();

        $session = [
            'logged_in' => true,
            'user_id' => 1,
            'user_type' => 1
        ];

        $postData = [
            'id_role' => $insertId,
            'name_role' => 'Rôle Modifié',
            'priority_role' => 7,
            'min_assignation_first_technician_role' => 1,
            'max_assignation_first_technician_role' => 10
        ];

        $result = $this->withSession($session)
            ->post('/helpdesk/role/saveRole/' . $insertId, $postData);

        // Vérifier la redirection
        $result->assertRedirect();
        $result->assertRedirectTo('/helpdesk/role/listRole');

        // Vérifier que le rôle a été modifié
        $role = $this->roles_model->getRoleByID($insertId);
        $this->assertNotNull($role);
        $this->assertEquals('Rôle Modifié', $role['name_role']);
        $this->assertEquals(7, $role['priority_role']);
    }

    /**
     * Test de l'affichage de la page de confirmation de suppression
     */
    public function testDeleteRoleConfirmationDisplay()
    {
        // Créer un rôle de test
        $roleData = [
            'name_role' => 'Rôle à Supprimer',
            'priority_role' => 1
        ];
        $roleId = $this->roles_model->insert($roleData);
        $insertId = $this->roles_model->insertID();

        $session = [
            'logged_in' => true,
            'user_id' => 1,
            'user_type' => 1
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/role/deleteRole/' . $insertId . '/0');

        $result->assertStatus(200);
        $result->assertSee('Rôle à Supprimer', 'body');
    }

    /**
     * Test de la suppression d'un rôle
     */
    public function testDeleteRoleExecutesDeletion()
    {
        // Créer un rôle de test
        $roleData = [
            'name_role' => 'Rôle à Supprimer',
            'priority_role' => 1
        ];
        $roleId = $this->roles_model->insert($roleData);
        $insertId = $this->roles_model->insertID();

        $session = [
            'logged_in' => true,
            'user_id' => 1,
            'user_type' => 1
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/role/deleteRole/' . $insertId . '/1');

        // Vérifier la redirection
        $result->assertRedirect();
        $result->assertRedirectTo('/helpdesk/role/listRole');

        // Vérifier que le rôle a été supprimé
        $role = $this->roles_model->getRoleByID($insertId);
        $this->assertNull($role);
    }

    /**
     * Test de la redirection si le rôle n'existe pas lors de la modification
     */
    public function testSaveRoleRedirectsIfRoleNotFound()
    {
        $session = [
            'logged_in' => true,
            'user_id' => 1,
            'user_type' => 1
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/role/saveRole/99999');

        $result->assertRedirect();
        $result->assertRedirectTo('/helpdesk/role/listRole');
    }

    /**
     * Test de la redirection si le rôle n'existe pas lors de la suppression
     */
    public function testDeleteRoleRedirectsIfRoleNotFound()
    {
        $session = [
            'logged_in' => true,
            'user_id' => 1,
            'user_type' => 1
        ];

        $result = $this->withSession($session)
            ->get('/helpdesk/role/deleteRole/99999/0');

        $result->assertRedirect();
        $result->assertRedirectTo('/helpdesk/role/listRole');
    }
}

