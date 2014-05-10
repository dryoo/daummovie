<?php
/**
 * DokuWiki Plugin daummovie (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  V_L <dryoo@live.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class syntax_plugin_daummovie extends DokuWiki_Syntax_Plugin {
    /**
     * @return string Syntax mode type
     */
    public function getType() {
        return 'container';
    }
    /**
     * @return string Paragraph type
     */
    public function getPType() {
        return 'normal';
    }
    /**
     * @return int Sort order - Low numbers go before high numbers
     */
    public function getSort() {
        return 319;
    }

    /**
     * Connect lookup pattern to lexer.
     *
     * @param string $mode Parser mode
     */
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern
			('\{\{daummovie>[^}]*\}\}',$mode,'plugin_daummovie');
    }

    /**
     * Handle matches of the daummovie syntax
     *
     * @param string $match The match of the syntax
     * @param int    $state The state of the handler
     * @param int    $pos The position in the document
     * @param Doku_Handler    $handler The handler
     * @return array Data for the renderer
     */
    public function handle($match, $state, $pos, &$handler){
		
        $data = array();
        $match = substr($match,12,-2); //strip markup from start and end
        $apikey= $this->getConf('daumapikey');
		$request = 'http://apis.daum.net/contents/movie?apikey='.$apikey
				.'&output=json&result=3&q='.urlencode($match);
		
		
		
				for ($tries=1; $tries<10;$tries++) {
					$response = json_decode($this->curl($request));
					if ($response->channel->item[0]->title[0]->content!=null) break;
					usleep(100000);
				}
				
		
		
		
		$movie=$response->channel->item[0];
		
		
		$data['thumbnail']	=$movie->thumbnail[0]->content;
		$data['title']		=$movie->title[0]->content;
		$data['link']		=$movie->title[0]->link;
		$data['eng_title']	=$movie->eng_title[0]->content;
		$data['year']		=$movie->year[0]->content;
		$data['director']	=$movie->director[0]->content;
		$data['nation']		=$movie->nation[0]->content;
		$data['grades']		=$movie->grades[0]->content;
		$data['genre']		=$this->_join($movie->genre,true);
		$data['actor']		=$this->_join($movie->actor,true); 
		$data['open_info']	=$this->_join($movie->open_info);
		$data['query']		= $match." (".$tries.") ".$response->code;
        return $data;
    }

    /**
     * Render xhtml output or metadata
     *
     * @param string         $mode      Renderer mode (supported modes: xhtml)
     * @param Doku_Renderer  $renderer  The renderer
     * @param array          $data      The data from the handler() function
     * @return bool If rendering was successful.
     */
    public function render($mode, &$renderer, $data) {
        if($mode != 'xhtml') return false;
		if ($data['title']==null) 
		{   $renderer->doc.="<small>[DaumMovie: ".$data['query']."]</small>";
			$renderer->doc.= date(DATE_RFC2822);
                return false;}
		$renderer->doc.= "<div class=\"daummovie\">";
		$renderer->doc.= "<img src=\"".$data['thumbnail']."\" alt=\"\" style=\"margin-right:1em;\">";
        
        
	/*	$renderer->doc.= "<a href=\"".$data['link']."\" rel=\"nofollow\">".$data['title']." <small>".$data['year']."</small></a><br>";
		$renderer->doc.= $data['eng_title']."<br>";
		$renderer->doc.= "<strong>감독</strong> : ".$data['director']."<br>";
		$renderer->doc.= "<strong>평점</strong> : ".$data['grades']."<br>";
		$renderer->doc.= "<strong>정보</strong> : ".$data['nation']." ".$data['open_info']."<br>";
		$renderer->doc.= "<strong>분류</strong> : ".$data['genre']."<br>";
		$renderer->doc.= "<strong>출연</strong> : ".$data['actor']."<br></small></div>";  */
        
        $renderer->doc.= "<a href=\"".$data['link']."\" rel=\"nofollow\">".$data['title']." <small>".$data['year']."</small></a><br><small>";
     
        $wdata.="".$data['eng_title']." \r\n ";
        $wdata.="     * 감독 : [[".$data['director']."]]  \r\n";
        $wdata.="     * 평점 : ".$data['grades']."  \r\n";
        $wdata.="     * 정보 : [[".$data['nation']."]] ".$data['open_info']." \r\n";
        $wdata.="     * 분류 : ".$data['genre']."  \r\n";
        $wdata.="     * 출연 : ".$data['actor']."  \r\n";
            
        
        $renderer->doc.=p_render('xhtml',p_get_instructions($wdata),$info);
        
        
		$renderer->doc.= "</small></div>"; 
		//$renderer->doc.= $data['query'];
		return true;

}
	function _join($xxx,$link=false){
		if ($xxx)
		{
			foreach ($xxx as $val) {
				if ($link) $out.=" [[".$val->content."]] ";
                   else $out.="".$val->content." ";
			}
          return $out;
	  } else
		  return false;
	}

	function curl($url,$param=false) 
	{ // http://www.partner114.com/bbs/board.php?bo_table=B07&wr_id=126
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        if ($param) curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$g = curl_exec($ch);
		curl_close($ch);
		return $g;
	}

}

// vim:ts=4:sw=4:et:
