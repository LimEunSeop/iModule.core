<?php
/**
 * 이 파일은 iModule 의 일부입니다. (https://www.imodule.kr)
 *
 * 사이트에 영향을 받지 않고 모듈의 외부컨테이너를 호출한다.
 * 
 * @file /modules/index.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 5. 27.
 */
REQUIRE_ONCE str_replace(DIRECTORY_SEPARATOR.'modules','',__DIR__).'/configs/init.config.php';

/**
 * iModule 코어를 선언하고, 모듈 컨테이너를 불러온다.
 * 
 * @see /classes/iMdoule.class.php
 */
$IM = new iModule();

/**
 * 컨테이너 호출변수
 */
$module = Request('_module');
$container = Request('_container');
$view = Request('_view');
$idx = Request('_idx');
$IM->menu = '#';
$IM->page = '#';

if (strpos($container,'@') === 0) {
	$container = preg_replace('/^@/','',$container);
	$IM->removeTemplet();
	define('__IM_CONTAINER_POPUP__',true);
}
$IM->setContainerMode($module,$container);

define('__IM_CONTAINER__',true);

/**
 * 호출변수가 없거나 호출하려는 모듈이 설치가 되어 있지 않은 경우, 에러메세지를 출력한다.
 */
if ($module === null || $container == null || $IM->getModule($module) === null) {
	return $IM->printError('NOT_FOUND_MODULE');
}

/**
 * 모듈에 getContainer 가 없다면 해당모듈은 외부컨테이너를 지원하지 않으므로, 에러메세지를 출력한다.
 */
if (method_exists($IM->getModule($module),'getContainer') === false) {
	return $IM->printError('NOT_SUPPORT_CONTAINER');
}

/**
 * 외부 컨테이너를 호출하여 출력한다.
 */
echo $IM->getModule($module)->getContainer($container);
?>