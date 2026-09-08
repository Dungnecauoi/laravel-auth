<?php

namespace Duxbo\LaravelAuth\Actions;

class DeleteUser
{
    public function delete($user): void
    {
        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }

        $user->delete();
    }
}
