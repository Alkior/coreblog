<?php

namespace Controllers;

use Models\ArticleModel;
use core\RND;
use core\Request;
use Core\Validator;
use core\Users;

class ArticleController extends BaseController
{
	
	public function indexAction()
	{
		$mArticle = ArticleModel::Instance();
		$article = $mArticle->all();		
		
		if ($article === false){
			echo 'Возникла ошибка';
		}
		elseif ($article == []){
			echo 'Нет новостей для отображения!';
		}
		$this->content = RND::render('view/index.html.php', [
			'article' => $article
		]);
		$this->title = 'Главная блога';
	}

	public function oneAction()
	{
	    if(!Users::isAuth()){
	        $this->getRedirect('/');
        }
		if(!isset($this->request->getGet()['id'])){
			$this->Page404();
		}	
		$mArticle = ArticleModel::Instance();
		$article = $mArticle->get($this->request->getGet()['id']);		
		
		if ($article === false){
			$this->Page404();
		}
		elseif ($article == []){
			echo 'Нет новостей для отображения!';
		}
		$this->content = RND::render('view/article.html.php', [
			'article' => $article
		]);
		$this->title = $article['title'];
	}

	public function addAction()
	{
        if(!Users::isAuth()){
            $this->getRedirect('/');
        }

		$mArticle = ArticleModel::Instance();
				
		
		if(count($this->request->getPost()) > 0 ){
			$errors = $mArticle->isValidate($this->request->getPost()['title'], $this->request->getPost()['content']);
			if(empty($errors)){

				$article = $mArticle->write(htmlspecialchars($this->request->getPost()['title']), htmlspecialchars($this->request->getPost()['content']));
				$this->getRedirect('/');
			}
			else {
				$this->getRedirect('/');
			}
		}
		$this->content = RND::render('view/add.html.php', [
			'article' => $article,
			'errors' => $errors
		]);
		$this->title = 'Добавить новость.';

	}

	public function editAction()
	{
        if(!Users::isAuth()){
            $this->getRedirect('/');
        }
		if(!isset($this->request->getGet()['id'])){
			$this->Page404();
		}
		$mArticle = ArticleModel::Instance();
		$article = $mArticle->get($this->request->getGet()['id']);
		if(!$article){
			$this->Page404();
		}
		
		
		if(count($this->request->getPost()) > 0 ){
			$errors = $mArticle->updateValid($this->request->getPost()['title'], $this->request->getPost()['content'], $this->request->getGet()['id']);
			if(empty($errors)){
			$res = $mArticle->updateM(htmlspecialchars($this->request->getPost()['title']), htmlspecialchars($this->request->getPost()['content']), $this->request->getGet()['id']);			
				if($res == false){
					$errors[] = NULL;
					$errors['tit'] = 'Критическая ошибка';
				}
				$this->getRedirect('/');
			}
		}
		/*else{			
			$errors['tit'] = 'Введите название на которое жалаете изменить';
		}*/

		$this->content = RND::render('view/edit.html.php', [
			'article' => $article,
			'errors' => $errors
		]);
		$this->title = 'Изменение статьи ' . $article['title'];
	}

	public function aboutAction(){
		$this->content = RND::render('view/about.html.php');
		$this->title = 'Немного об этом блоге';
	}

	public function delAction()
	{
	    
		$mArticle = ArticleModel::Instance();
		$article = $mArticle->del($this->request->getGet()['id']);
		if(!empty($article)){
			$this->getRedirect('/');
		}

	}
}

