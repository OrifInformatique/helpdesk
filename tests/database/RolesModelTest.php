<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Helpdesk\Models\Roles_model;

/**
 * Test for Roles_model
 * 
 * @internal
 */
final class RolesModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    /**
     * Test for getRoles() method
     */
    public function testGetRoles()
    {
        $model = new Roles_model();

        // Get all roles
        $roles = $model->getRoles();

        // Check that the result is an array
        $this->assertIsArray($roles);

        // Check that there is at least one role (if the table contains data)
        if (!empty($roles)) {
            // Check the structure of the first role
            $firstRole = $roles[0];
            $this->assertObjectHasProperty('id_role', $firstRole);
            $this->assertObjectHasProperty('name_role', $firstRole);
            $this->assertObjectHasProperty('priority_role', $firstRole);
        }
    }

    /**
     * Test for getRoleByID() method with a valid ID
     */
    public function testGetRoleByIDValid()
    {
        $model = new Roles_model();

        // Get all roles to obtain a valid ID
        $allRoles = $model->getRoles();

        if (!empty($allRoles)) {
            $firstRoleId = $allRoles[0]->id_role;

            // Get a role by its ID
            $role = $model->getRoleByID($firstRoleId);

            // Check that the result is not null
            $this->assertNotNull($role);

            // Check that the ID matches (if the role is an object)
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
     * Test for getRoleByID() method with an invalid ID
     */
    public function testGetRoleByIDInvalid()
    {
        $model = new Roles_model();

        // Test with an ID that probably doesn't exist
        $role = $model->getRoleByID(99999);

        // Check that the result is null
        $this->assertNull($role);
    }

    /**
     * Test that getRoles() method returns the same results as findAll()
     */
    public function testGetRolesEqualsFindAll()
    {
        $model = new Roles_model();

        $rolesFromMethod = $model->getRoles();
        $rolesFromFindAll = $model->findAll();

        // Check that both methods return the same number of results
        $this->assertCount(count($rolesFromFindAll), $rolesFromMethod);
    }
}

