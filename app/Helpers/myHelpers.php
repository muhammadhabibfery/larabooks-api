<?php

use App\User;

/**
 * Identify the user role
 *
 * @param  array $availableRoles
 * @param  array $userRole
 * @return boolean
 */
function checkRole(array $availableRoles, array $userRole)
{
    // return count(array_intersect($availableRoles, $userRole)) > 0;
    return in_array(head($userRole), $availableRoles);
}

/**
 * query a user who create or update or delete related data
 *
 * @param  int $id
 * @return string
 */
function createdUpdatedDeletedBy(int $id)
{
    return User::find($id)->name;
}
