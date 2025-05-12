<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509115254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX categoria_nombre_key RENAME TO UNIQ_NOMBRE_CATEGORIA');
        $this->addSql('ALTER TABLE detalle_pedido ALTER id_pedido DROP NOT NULL');
        $this->addSql('ALTER TABLE detalle_pedido ALTER id_producto DROP NOT NULL');
        $this->addSql('ALTER TABLE envio DROP CONSTRAINT envio_id_pedido_fkey');
        $this->addSql('DROP INDEX IF EXISTS idx_754737d5e2dba323');
        $this->addSql('ALTER TABLE envio DROP id_pedido');
        $this->addSql('ALTER TABLE envio ALTER fecha_envio TYPE DATE');
        $this->addSql('ALTER TABLE envio ALTER fecha_envio DROP DEFAULT');
        $this->addSql('ALTER TABLE envio ALTER fecha_entrega_estimada TYPE DATE');
        $this->addSql('ALTER TABLE envio ALTER fecha_entrega_real TYPE DATE');
        $this->addSql('ALTER TABLE pedidos ALTER fecha_pedido DROP DEFAULT');
        $this->addSql('ALTER TABLE producto DROP unidades_vendidas');
        $this->addSql('ALTER TABLE producto ALTER id_categoria DROP NOT NULL');
        $this->addSql('ALTER TABLE producto ALTER stock DROP DEFAULT');
        $this->addSql('ALTER TABLE usuario ALTER fecha_registro DROP DEFAULT');
        $this->addSql('ALTER INDEX usuario_email_key RENAME TO UNIQ_2265B05DE7927C74');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER INDEX uniq_nombre_categoria RENAME TO categoria_nombre_key');
        $this->addSql('ALTER TABLE pedidos ALTER fecha_pedido SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE producto ADD unidades_vendidas INT DEFAULT NULL');
        $this->addSql('ALTER TABLE producto ALTER id_categoria SET NOT NULL');
        $this->addSql('ALTER TABLE producto ALTER stock SET DEFAULT 0');
        $this->addSql('ALTER TABLE envio ADD id_pedido INT NOT NULL');
        $this->addSql('ALTER TABLE envio ALTER fecha_envio TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE envio ALTER fecha_envio SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE envio ALTER fecha_entrega_estimada TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE envio ALTER fecha_entrega_real TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE envio ADD CONSTRAINT envio_id_pedido_fkey FOREIGN KEY (id_pedido) REFERENCES pedidos (id_pedido) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_754737D5E2DBA323 ON envio (id_pedido)');
        $this->addSql('ALTER TABLE usuario ALTER fecha_registro SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER INDEX uniq_2265b05de7927c74 RENAME TO usuario_email_key');
        $this->addSql('ALTER TABLE detalle_pedido ALTER id_pedido SET NOT NULL');
        $this->addSql('ALTER TABLE detalle_pedido ALTER id_producto SET NOT NULL');
    }
}
