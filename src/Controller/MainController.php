<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
* Classe principal para o gerenciamento da dashboard criada para o projeto da disciplina de Produtos e Serviços da Informação
* Autor: Juliano Luiz da Silva 
* Email: julianoluizsilva00@gmail.com
*/

class MainController
{	
	private $session;

	// Injeção de dependência do serviço da sessão
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

	/**
	* Pagina inical, a partir desta o usuário faz o login e recebe a autenticação necessária para as outras páginas
	* Para acessar as próximas páginas o usuário deve ter um usuário e senha válido
	* @params Request
	* @return JsonResponse
	*
	* @Route("/")
    */
	public function index(Request $request)
	{
		$results = [];
		// verifica se é passado o usuário na url
		if(!empty($request->query->all()) && $request->query->all()['username']){

			// atribui o usuário da url na sessão
			$this->session->set('name', $request->query->all()['username']);

			// atrbiu uma resposta para a requisição
			$results = ['ok'];

		};
		
		return new JsonResponse($results);
		
	}

	/**
	* carrega as informações gerais da tela dashboard
	* @params $request
	* @return JsonResponse:
	* 		@array dados do usuário 
	*		@array dados genéricos do gráfico
	* @Route("/dashboard")
    */
	public function dashboard(Request $request)
	{

		// verifica sessão a procura do nome do usuário, se este existir atribui a chave 'name'
		$results = [
			'name'   => $this->session->get('name') ? $this->session->get('name') : 'anonimo',
			'alunos' => []
		];

		// verifica se a url passa a chave de segurança 'cidadejunio', caso positivo faz a conexão com o banco de dados e carrega os dados dos alunos para a exibição

		if(!empty($request->query->all()) && $request->query->all()['key'] == 'cidadejunior'){
			// caminho físico do banco de dados (no futuro esta configuração estará no arquivo .env)
			$host = 'localhost:C:\Users\Juliano\Desktop\CIDADE.FDB';

			// conexão ao banco passando usuário e senha
			$dbh = ibase_connect($host, 'SYSDBA', 'root');

			// query para visualizar todos os alunos
			$stmt = 'SELECT * FROM alunos';

			// execução da query
			$sth = ibase_query($dbh, $stmt);

			// os nomes de alunos são recebidos no array
			while ($row = ibase_fetch_object($sth)) {
			    $results['alunos'][] = $row->NICKNAME;
			}
			ibase_free_result($sth);

			// a conexão com o banco de dados é fechada
			ibase_close($dbh);

		};
		
		return new JsonResponse($results);
		
	}
}