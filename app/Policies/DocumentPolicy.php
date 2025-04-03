<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determina se o usuário pode visualizar a lista de documentos.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin'; // Apenas administradores podem ver todos os documentos
    }

    /**
     * Determina se o usuário pode visualizar um documento específico.
     */
    public function view(User $user, Document $document): bool
    {
        return $user->role === 'admin' || $user->id === $document->user_id;
    }

    /**
     * Determina se o usuário pode criar um documento.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin'; // Somente admin pode criar
    }

    /**
     * Determina se o usuário pode editar um documento.
     */
    public function update(User $user, Document $document): bool
    {
        return $user->role === 'admin' || $user->id === $document->user_id;
    }

    /**
     * Determina se o usuário pode excluir um documento.
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->roles()->where('name', 'admin')->exists();
    }

    /**
     * Determina se o usuário pode restaurar um documento excluído.
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determina se o usuário pode deletar permanentemente um documento.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->role === 'admin';
    }
}
