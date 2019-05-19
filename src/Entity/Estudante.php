<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="apresentacao.estudante")
 * @ORM\Entity(repositoryClass="App\Repository\EstudanteRepository")
 */
class Estudante
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_motivo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $empresa;

    /**
     * @ORM\Column(type="datetime")
     */
    private $data_entrada;

    /**
     * @ORM\Column(type="datetime")
     */
    private $data_saida;

    /**
     * @ORM\Column(type="integer")
     */
    private $sexo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $data_nascimento;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cidade;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIdMotivo(): ?int
    {
        return $this->id_motivo;
    }

    public function setIdMotivo(int $id_motivo): self
    {
        $this->id_motivo = $id_motivo;

        return $this;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function setEmpresa(string $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }

    public function getDataEntrada(): ?\DateTimeInterface
    {
        return $this->data_entrada;
    }

    public function setDataEntrada(\DateTimeInterface $data_entrada): self
    {
        $this->data_entrada = $data_entrada;

        return $this;
    }

    public function getDataSaida(): ?\DateTimeInterface
    {
        return $this->data_saida;
    }

    public function setDataSaida(\DateTimeInterface $data_saida): self
    {
        $this->data_saida = $data_saida;

        return $this;
    }

    public function getSexo(): ?int
    {
        return $this->sexo;
    }

    public function setSexo(int $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getDataNascimento(): ?\DateTimeInterface
    {
        return $this->data_nascimento;
    }

    public function setDataNascimento(\DateTimeInterface $data_nascimento): self
    {
        $this->data_nascimento = $data_nascimento;

        return $this;
    }

    public function getCidade(): ?string
    {
        return $this->cidade;
    }

    public function setCidade(string $cidade): self
    {
        $this->cidade = $cidade;

        return $this;
    }
}
