<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Helpdesk\Models\Roles_model;

/**
 * Test pour le modèle Roles_model
 * 
 * @internal
 */
final class RolesModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    /**
     * Test de la méthode getRoles()
     */
    public function testGetRoles()
    {
        $model = new Roles_model();

        // Récupérer tous les rôles
        $roles = $model->getRoles();

        // Vérifier que le résultat est un tableau
        $this->assertIsArray($roles);

        // Vérifier qu'il y a au moins un rôle (si la table contient des données)
        if (!empty($roles)) {
            // Vérifier la structure du premier rôle
            $firstRole = $roles[0];
            $this->assertObjectHasAttribute('id_role', $firstRole);
            $this->assertObjectHasAttribute('name_role', $firstRole);
            $this->assertObjectHasAttribute('priority_role', $firstRole);
        }
    }

    /**
     * Test de la méthode getRoleByID() avec un ID valide
     */
    public function testGetRoleByIDValid()
    {
        $model = new Roles_model();

        // Récupérer tous les rôles pour obtenir un ID valide
        $allRoles = $model->getRoles();

        if (!empty($allRoles)) {
            $firstRoleId = $allRoles[0]->id_role;

            // Récupérer un rôle par son ID
            $role = $model->getRoleByID($firstRoleId);

            // Vérifier que le résultat n'est pas null
            $this->assertNotNull($role);

            // Vérifier que l'ID correspond (si le rôle est un objet)
            if (is_object($role)) {
                $this->assertEquals($firstRoleId, $role->id_role);
            } elseif (is_array($role)) {
                $this->assertEquals($firstRoleId, $role['id_role']);
            }
        } else {
            $this->markTestSkipped('Aucun rôle dans la base de données pour tester');
        }
    }

    /**
     * Test de la méthode getRoleByID() avec un ID invalide
     */
    public function testGetRoleByIDInvalid()
    {
        $model = new Roles_model();

        // Tester avec un ID qui n'existe probablement pas
        $role = $model->getRoleByID(99999);

        // Vérifier que le résultat est null
        $this->assertNull($role);
    }

    /**
     * Test que la méthode getRoles() retourne les mêmes résultats que findAll()
     */
    public function testGetRolesEqualsFindAll()
    {
        $model = new Roles_model();

        $rolesFromMethod = $model->getRoles();
        $rolesFromFindAll = $model->findAll();

        // Vérifier que les deux méthodes retournent le même nombre de résultats
        $this->assertCount(count($rolesFromFindAll), $rolesFromMethod);
    }
}

