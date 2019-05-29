<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Estudante;



/**
* Classe principal para o gerenciamento da dashboard criada para o projeto da disciplina de Produtos e Serviços da Informação
* Autor: Juliano Luiz da Silva 
* Email: julianoluizsilva00@gmail.com
*/

class MainController extends AbstractController
{	
	private $session;

    public function __construct(UrlGeneratorInterface $urlGenerator, SessionInterface $session)
    {
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
    }

	/**
	* Pagina inical, a partir desta o usuário faz o login e recebe a autenticação necessária para as outras páginas	
	*
	* @Route("/")
    */
	public function index()
	{
		return new RedirectResponse($this->urlGenerator->generate('app_login'));	
	}

	/**
	* @Route("/dashboard", name="dashboard")
    */
	public function dashboard(Request $request, EntityManagerInterface $em)
	{
		$empresas = $this->getDoctrine()
        ->getRepository(Estudante::class)->findEmpresas(5);

		$estudantes_mes = $this->getDoctrine()
        ->getRepository(Estudante::class)->estudantesMes();

        $qualidade_dados = $this->getDoctrine()
        ->getRepository(Estudante::class)->qualidadeDados();

        $count_municipios = $this->getDoctrine()
        ->getRepository(Estudante::class)->countMunicipios();
		
		$count_status = $this->getDoctrine()
        ->getRepository(Estudante::class)->countStatus();

        $empresas_alunos = $this->getDoctrine()
        ->getRepository(Estudante::class)->empresasAlunos();

        $count_age = $this->getDoctrine()
        ->getRepository(Estudante::class)->countAge();

		return $this->render('dashboard.html.twig', [
            'title' => 'Dashboard',
            'empresas' => json_encode($empresas, true),
            'qualidade_dados' => json_encode($qualidade_dados, true),
            'municipios' => json_encode($count_municipios, true),
            'age' => json_encode($count_age, true),
            'status' => json_encode($count_status, true),
            'estudantes' => json_encode($estudantes_mes),
            'empresas_alunos' => json_encode($empresas_alunos)
        ]);
		
	}

	/**
	* @Route("/configuracoes", name="configuracoes")
    */
	public function configuracoes(Request $request)
	{

		return $this->render('configuracoes.html.twig', [
            'title' => 'Configurações',
        ]);
		
	}

	/**
	* @Route("/periodo", name="periodo")
    */
	public function setPeriodo(Request $request)
	{

		$date = $request->getContent();

		print_r($date);

		die();
		
	}
}