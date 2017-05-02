<?php
class manager{
    
    private $articles;
    private $menuItems;
    private $configItems;
    private $plugins;
    private $langs;
    
    public function __construct(){
        // todo : dispatcher le code dans des methodes (private)
        // chargement des plugins
        $dir = utilScanDir('plugin/');
        foreach($dir['dir'] as $file){
            include_once('plugin/'.$file.'/'.$file.'.php');
            $plugin = new plugin();
            $plugin->set('id', $file);
	    $plugin->install();
            $temp = $plugin->getConfigArray();
            $plugin->set('name', $temp['name']);
            $plugin->set('version', $temp['version']);
            $plugin->set('author', $temp['author']);
            $plugin->set('priority', $temp['priority']);
            $this->plugins[] = $plugin;
        }
        // chargement des articles
        $this->articles = array();
        $dir = utilScanDir('data/article/');
        foreach($dir['file'] as $file){
            $temp = utilReadJsonFile('data/article/'.$file);
            $article = new article();
            $article->set('id', $temp['id']);
            $article->set('name', $temp['name']);
            $article->set('content', $temp['content']);
            $article->set('date', $temp['date']);
            $article->set('type', $temp['type']);
            $article->set('homepage', $temp['homepage']);
            $this->articles[] = $article;
        }
        // chargement des items menu
        $this->menuItems = array();
        $dir = utilScanDir('data/menu/');
        $level1 = array();
        $level2 = array();
        foreach($dir['file'] as $file){
            $temp = utilReadJsonFile('data/menu/'.$file);
            if($temp['idParent'] == 0){
                $level1[] = $temp;
                if(!array_key_exists($level2[$temp['id']], $level2)) $level2[$temp['id']] = array();
            }
            else{
                $level2[$temp['idParent']][] = $temp;
            }
        }
        $level1 = utilSort2DimArray($level1, 'position', 'asc');
        foreach($level2 as $k=>$temp){
            $level2[$k] = utilSort2DimArray($temp, 'position', 'asc');
        }
        foreach($level1 as $k=>$temp){
            $item = new menuItem();
            $item->set('id',$temp['id']);
            $item->set('name', $temp['name']);
            $item->set('idParent', $temp['idParent']);
            $item->set('url', $temp['url']);
            $item->set('position', $temp['position']);
            $this->menuItems['level1'][] = $item;
            $this->menuItems['level2'][$temp['id']] = array();
        }
        foreach($level2 as $k=>$temp){
            foreach($temp as $k=>$temp2){
                $item = new menuItem();
                $item->set('id',$temp2['id']);
                $item->set('name', $temp2['name']);
                $item->set('idParent', $temp2['idParent']);
                $item->set('url', $temp2['url']);
                $item->set('position', $temp2['position']);
                $this->menuItems['level2'][$temp2['idParent']][] = $item;
            }
        }
        // chargement des items config
        $this->configItems = array();
        $temp = utilReadJsonFile('data/core.json');
        foreach($temp as $k=>$v){
            $item = new configItem();
            $item->set('key', $k);
            $item->set('val', $v);
            $this->configItems[] = $item;
        }
        // chargement des fichiers langue core
        // todo : gestion des langues a revoir ?
        $this->langs = array();
        $dir = utilScanDir('core/lang/');
        foreach($dir['file'] as $file){
            $temp = utilReadJsonFile('core/lang/'.$file);
            $k = substr($file, 0, 2);
            $this->langs[$k] = $temp;
        }
        // chargement des fichiers langue plugins
        foreach($this->plugins as $plugin){
                $dir = utilScanDir('plugin/'.$plugin->get('id').'/lang/');
                foreach($dir['file'] as $file){
                        $temp = utilReadJsonFile('plugin/'.$plugin->get('id').'/lang/'.$file);
                        $k = substr($file, 0, 2);
                        // merge
                        $this->langs[$k] = array_merge($this->langs[$k], $temp);
                }
        }
        $_SESSION['lang'] = $this->langs[$k];
        // hook
        eval(callHook('managerConstruct'));
    }
    
    // retourne la version du core
    public function getVersion(){
        return file_get_contents('core/version');
    }
    
    // liste les langues
    // todo : renommer en listLangsArray ?
    public function listLangs(){
        $langs = $this->langs;
        return $langs;
    }
    
    // retourne une valeur de configuration
    // todo renommer en getConfigValue ?
    public function getConfigItem($key){
        foreach($this->configItems as $item){
            if($key == $item->get('key')) break;
        }
        return $item->get('val');
    }
    
    // sauvegarde une valeur de configuration
    public function saveConfigItem($newItem){
        $data = array();
        foreach($this->configItems as $k=>$item){
            if($newItem->get('key') == $item->get('key')){
                $item = $newItem;
                // mise a jour du tableau d'items courant
                $this->configItems[$k] = $item;
            }
            $data[$item->get('key')] = $item->get('val');
        }
        utilWriteJsonFile('data/core.json', $data);
    }
    
    // liste les plugins
    public function listPlugins($activateOnly = false){
        foreach($this->plugins as $k=>$plugin){
            if($activateOnly && $plugin->get('activate') == 0) unset($this->plugins[$k]);
        }
        return $this->plugins;
    }
    
    // retourne un plugin
    public function getPlugin($id){
        foreach($this->listPlugins() as $plugin){
            if($plugin->get('id') == $id) break;
        }
        return $article;
    }
    
    // liste les articles
    public function listArticles($byType = false){
        $data = $this->articles;
        foreach($data as $k=>$article){
            if($byType && $byType != $article->get('type')) unset($data[$k]);
        }
        return $data;
    }
    
    // retourne un article
    public function getArticle($id){
        foreach($this->listArticles() as $article){
            if($article->get('id') == $id) break;
        }
        return $article;
    }
    
    // retourne l'article defini comme page d'accueil
    public function getArticleHomepage(){
        foreach($this->listArticles() as $article){
            if($article->get('homepage')) break;
        }
        return $article;
    }
    
    // sauvegarde un article
    public function saveArticle($article){
        $data['id'] = $article->get('id');
        $data['name'] = $article->get('name');
        $data['content'] = $article->get('content');
        $data['date'] = $article->get('date');
        $data['homepage'] = $article->get('homepage');
        $data['type'] = $article->get('type');
        utilWriteJsonFile('data/article/'.$article->get('id').'.json', $data);
    }
    
    // liste les items menu
    public function listMenuItems($level = 'level1'){
        $items = $this->menuItems[$level];
        return $items;
    }
    
    // retourne un item menu
    public function getMenuItem($id){
        foreach($this->listMenuItems() as $item){
            if($item->get('id') == $id) return $item;
            $temp = $this->listMenuItems('level2');
            foreach($temp[$item->get('id')] as $item){
                if($item->get('id') == $id) return $item;
            }
        }
    }
    
    // sauvegarde un item menu
    public function saveMenuItem($item){
        $data['id'] = $item->get('id');
        $data['name'] = $item->get('name');
        $data['idParent'] = $item->get('idParent');
        $data['url'] = $item->get('url');
        $data['position'] = $item->get('position');
        utilWriteJsonFile('data/menu/'.$item->get('id').'.json', $data);
    }
    
    // supprime un item menu
    // todo : supression des items enfants (supression propre)
    public function delMenuItem($item){
        unlink('data/menu/'.$item->get('id').'.json');
    }
    
    // cree les fichiers necessaires
    public function install(){
        mkdir('data/');
        mkdir('data/article/');
        mkdir('data/menu/');
		mkdir('data/plugin/');
        $config = array(
            'name' => 'Démo',
            'theme' => 'default',
            'url' => getSiteUrl(),
            'lang' => 'fr',
        );
        utilWriteJsonFile('data/core.json', $config);
        $id = uniqid();
        $article = array(
            'id' => $id,
            'name' => 'Accueil',
            'content' => '<p>L\'installation s\'est déroulée avec succès !<br />Rendez-vous sur le site officiel de 99ko pour télécharger des plugins et des thèmes.</p><p>Cras mattis consectetur purus sit amet fermentum. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Sed posuere consectetur est at lobortis. Sed posuere consectetur est at lobortis.</p><p>Maecenas faucibus mollis interdum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Etiam porta sem malesuada magna mollis euismod.</p>',
            'date' => date('Y-m-d H:i:s'),
            'type' => 'page',
            'homepage' => 1
        );
        utilWriteJsonFile('data/article/'.$id.'.json', $article);
        $id2 = uniqid();
        $menu = array(
            'id' => $id2,
            'name' => 'Accueil',
            'url' => '?article='.$id,
            'position' => 1,
            'idParent' => 0
        );
        utilWriteJsonFile('data/menu/'.$id2.'.json', $menu);
        $id2 = uniqid();
        $menu = array(
            'id' => $id2,
            'name' => 'Blog',
            'url' => '?news',
            'idArticle' => '',
            'position' => 2,
            'idParent' => 0
        );
        utilWriteJsonFile('data/menu/'.$id2.'.json', $menu);
        $id = uniqid();
        $article = array(
            'id' => $id,
            'name' => 'Let’s connect!',
            'content' => '<p>Vestibulum id ligula porta felis euismod semper. Morbi leo risus, porta ac <a href="http://www.starck.io">consectetur</a> ac, vestibulum at eros. Nullam id dolor id nibh ultricies vehicula ut id elit. Donec ullamcorper nulla non metus auctor fringilla.</p><blockquote><p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec id elit non mi porta gravida at eget metus.</p></blockquote><p>Nullam id dolor id nibh <strong>ultricies vehicula ut id elit</strong>. Curabitur blandit tempus porttitor. Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>',
            'date' => date('Y-m-d H:i:s'),
            'type' => 'page',
            'homepage' => 0
        );
        utilWriteJsonFile('data/article/'.$id.'.json', $article);
        $id3 = uniqid();
        $menu = array(
            'id' => $id3,
            'name' => 'Contact',
            'url' => '?article='.$id,
            'position' => 3,
            'idParent' => 0
        );
        utilWriteJsonFile('data/menu/'.$id3.'.json', $menu);
        $id = uniqid();
        $article = array(
            'id' => $id,
            'name' => 'Article n°1',
            'content' => '<figure><img src="theme/default/demo/jon-tyson-228428.jpg" alt="anon"><figcaption>Petite description de l\'image.</figcaption></figure><p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Aenean lacinia bibendum nulla sed consectetur. Nullam id dolor id nibh ultricies vehicula ut id elit. Donec ullamcorper nulla non metus auctor fringilla.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Aenean lacinia bibendum nulla sed consectetur. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>',
            'date' => date('Y-m-d H:i:s'),
            'type' => 'news',
            'homepage' => 0
        );
        utilWriteJsonFile('data/article/'.$id.'.json', $article);
        $id = uniqid();
        $article = array(
            'id' => $id,
            'name' => 'Article n°2',
            'content' => '<figure><img src="theme/default/demo/patrick-tomasso-216284.jpg" alt="peinture"><figcaption>Petite description de l\'image.</figcaption></figure><p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nullam id dolor id nibh ultricies vehicula ut id elit. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vitae elit libero, a pharetra augue. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p><p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Maecenas faucibus mollis interdum.</p>',
            'date' => date('Y-m-d H:i:s'),
            'type' => 'news',
            'homepage' => 0
        );
        utilWriteJsonFile('data/article/'.$id.'.json', $article);
    }
    
}
?>