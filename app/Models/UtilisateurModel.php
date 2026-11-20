<?php
class UtilisateurModel extends Model
{
    protected string $table = 'utilisateur';
    protected string $primaryKey = 'id';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM utilisateur WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function nbClients(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM utilisateur WHERE role = 'client'")->fetchColumn();
    }
}
