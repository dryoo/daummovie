======도쿠위키 다음영화정보 플러긴 (DaumMovie plugin)======

[[http://www.daum.net/|다음]]의 [[http://dna.daum.net/apis/dashboard|Open API]]를 이용해서 영화정보를 가져오는 [[도쿠위키]]플러긴이다. 

{{daummovie>올드보이 2003}}
 

   * [[github|깃헙]]: https://github.com/dryoo/daummovie
   * [[도쿠위키|도쿠위키]]: https://dokuwiki.org/plugin:daummovie

=====설치=====

  * {{https://github.com/dryoo/daummovie/archive/master.zip|다운로드}}
  * https://github.com/dryoo/daummovie/archive/master.zip


다운로드 후 압축을 잘 풀어 적당한 위치에 넣거나,
플러긴 매니져를 이용해서 설치 후 
 
=====다음API 신청=====

[[http://dna.daum.net/myapi/dataapi/new|]]에서 데이터형 API를 컨텐트 형식으로 신청하고,

{{tech/daummovie_2.jpg|}}

=====설정=====
아까 얻은 ''Apikey''를 도쿠위키의 DaumMovie plugin 설정에 입력한다. 


{{tech/daummovie_3.jpg}}

=====사용법=====
이제 본문에 
  {{daummovie>영화제목}}

이렇게 넣어주면 된다. 

  {{daummovie>아키라}}
 

===== 변경=====

  * curl 함수로 변경...
  * 되다 안되다 하길래 봤더니, 다음영화정보 서버가 가끔 지 꼴릴 때만 대답을 하더라... 제대로된 답을 할 때까지 계속 시도를 하도록 바꿈.. 
  * 링크 추가.
  * 자료구조 변화에 맞게 수정함. 


=====버그=====

버그 신고해주라... 
