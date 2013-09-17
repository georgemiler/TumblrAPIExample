<?php
class HomeController extends AppController {
    public $components = array('Paginator'); 

    public $paginate = array(
        'limit'=> 10
    );

    private $posts;

    private function tumblrApiCall($blog_name, $url) {
        $api_key = Configure::read('tumblr_api');
        $url .= "&api_key=$api_key";

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_HEADER=> false,
            CURLOPT_RETURNTRANSFER=> true
        ));
        $rawdata = curl_exec($ch);

        $data = json_decode($rawdata, true);

        curl_close($ch);

        return $data;
    }

    private function numPosts($blog_name) {
        $url = "http://api.tumblr.com/v2/blog/$blog_name/info/?";
        $data = $this->tumblrApiCall($blog_name, $url);

        if($data && $data['response'] && $data['response']['blog']) {
            return $data['response']['blog']['posts'];
        }else {
            return -1;
        }
    }

    private function loadPostsRange($blog_name, $offset=0) {
        $api_key = Configure::read('tumblr_api');
        $url = "http://api.tumblr.com/v2/blog/$blog_name/posts/?offset=$offset";
        //echo "$url<br />";
        $data = $this->tumblrApiCall($blog_name, $url);

        if($data && $data['response'] && $data['response']['posts']) {
            $posts = $data['response']['posts'];
        }else {
            //Error parsing json
            $posts = array();
        }

        return $posts;
    }

    private function loadPosts($blog_name) {
        $api_key = Configure::read('tumblr_api');

        $this->posts = array();
        $numPosts = $this->numPosts($blog_name);
        if($numPosts <=0) {
            //No posts or an error occurred
            //TODO - Handle this error better
            return false;
        }

        $start = 0;
        $offset = 20;
        do {
            if($offset > $numPosts) {
                $offset = $numPosts-1;
            } 

            $posts = $this->loadPostsRange($blog_name, $start);
            $this->posts = array_merge($this->posts, $posts);

            //echo "$start - $offset<br />";
            //echo "count=" . count($posts) . "<hr />";
            $start = $offset+1;
            $offset += 20;
        } while($offset <= $numPosts);

        //echo "count=" . count($this->posts) . '<br />';

        if(count($this->posts) > 0) {
            //TODO - Add something to check the current site & number of posts so we don't reload from scratch every time
            $this->Home->deleteAll(array('1=1'));

            foreach($this->posts as $post) {
                $this->Home->create();
                $this->Home->save(array( 
                    "title"=> $post['slug'],
                    "url"=> $post['post_url'],
                    "post_date"=> $post['date'],
                    "post_id"=> $post['id'] 
                ));
            }

            //echo '<pre>' . print_r($this->posts, true) . '</pre>';
        }else {
            //Error parsing json
            //echo '<pre>' . print_r($rawdata, true) . '</pre>';
            return false;
        }

        return true;
    }

    public function display() {
        $this->layout = 'home';
        $data['blog_name'] = '';

        if($this->request->is('post')) {
            $data['blog_name'] = $this->request->data['blog_name'];

            //Do a little blog name cleanup (remove http://, append .tumblr.com if needed
            $data['blog_name'] = str_replace('http://', '', strtolower($data['blog_name']));
            $data['blog_name'] = trim($data['blog_name'], '/');
            if(strpos(strtolower($data['blog_name']), 'tumblr.com') === false) {
                $data['blog_name'] .= '.tumblr.com';
            }
            $this->Session->write("Home.blogName", $data['blog_name']);
        }else {
            $data['blog_name'] = $this->Session->read("Home.blogName");
        }

        if($this->loadPosts($data['blog_name'])) {
            $data['posts'] = $this->Paginator->paginate('Home');
        }

        $this->set($data);
    }
}
?>
