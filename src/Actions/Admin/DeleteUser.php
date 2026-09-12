<?php

namespace Duxbo\LaravelAuth\Actions\Admin;

use Illuminate\Auth\Access\AuthorizationException;

/**
 * Deleting *another* user from the admin screen — not the same operation
 * as Actions\DeleteUser, which is a user deleting their own account (and
 * requires a password re-confirmation the caller here never has).
 */
class DeleteUser
{
    /**
     * UserPolicy::delete() also refuses self-deletion, but Gate::before's
     * super-admin bypass (any ability, no exceptions) skips the policy
     * entirely for that role — so a super-admin's own "Xoá" click would
     * otherwise actually delete their own account. This check can't be
     * bypassed by any permission, super-admin included.
     */
    public function delete($actor, $target): void
    {
        if ($actor->getKey() === $target->getKey()) {
            throw new AuthorizationException(__('Không thể tự xoá tài khoản của chính mình.'));
        }

        $target->delete();
    }
}
