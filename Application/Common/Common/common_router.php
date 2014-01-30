<?php

// 路由动态获取url
function getSingleURLByID($ID, $type = 'single')
{
    $Posts = D('Posts');


    $url_base = U("Home/Post/" . $type);


    if (C('OUR_URL_MODEL') === 'native') {
        $URL = U("Home/Post/" . $type, array(
            'info' => $ID
        ));

        return $URL;
    } elseif (C('OUR_URL_MODEL') === 'post_id') {
        $URL = $url_base . '/' . $ID;
    } else if (C('OUR_URL_MODEL') === 'post_name') {
        $posts = $Posts->find($ID);
        $URL = $url_base . '/' . $posts ['post_name'];
    } else if (C('OUR_URL_MODEL') === 'year/month/post_name') {
        $posts = $Posts->find($ID);
        $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . $posts ['post_name'];
    } else if (C('OUR_URL_MODEL') === 'year/month/post_id') {
        $posts = $Posts->find($ID);
        $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . $ID;
    } else if (C('OUR_URL_MODEL') === 'year/post_id') {
        $posts = $Posts->find($ID);
        $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . $ID;
    } else if (C('OUR_URL_MODEL') === 'year/post_name') {
        $posts = $Posts->find($ID);
        $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . $posts ['post_name'];
    } else if (C('OUR_URL_MODEL') === 'year/month/day/post_id') {
        $posts = $Posts->find($ID);
        $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . getTimestamp($posts ['post_date'], 'day') . '/' . $ID;
    } else if (C('OUR_URL_MODEL') === 'year/month/day/post_name') {
        $posts = $Posts->find($ID);
        $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . getTimestamp($posts ['post_date'], 'day') . '/' . $posts ['post_name'];
    } else {
        $URL = $url_base . '/' . $ID;
    }

    return $URL;
}

// 路由动态获取url
function getTagURLByID($ID)
{
    $Tags = D('native');
    if (C('OUR_TAG_MODEL') === 'native') {
        $URL = U('Home/Tag/detail', array("info" => $ID));
    } elseif (C('OUR_TAG_MODEL') === 'ID') {
        $URL = U('/tag') . '/' . $ID;
    } else if (C('OUR_TAG_MODEL') === 'slug') {
        $tag = $Tags->find($ID);
        $URL = U('/tag') . '/' . $tag ['tag_slug'];
    } else {
        $URL = U('/tag') . '/' . $ID;
    }

    return $URL;
}

function getCatURLByID($ID)
{
    $Tags = D('Tags');
    if (C('OUR_CAT_MODEL') === 'native') {
        $URL = U('Home/Cat/detail', array("info" => $ID));
    } elseif (C('OUR_TAG_MODEL') === 'ID') {
        $URL = U('/cat') . '/' . $ID;
    } else if (C('OUR_TAG_MODEL') === 'slug') {
        $tag = $Tags->find($ID);
        $URL = U('/cat') . '/' . $tag ['tag_slug'];
    } else {
        $URL = U('/cat') . '/' . $ID;
    }

    return $URL;
}


