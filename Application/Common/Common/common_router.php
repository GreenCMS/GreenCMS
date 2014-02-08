<?php

// 路由动态获取url
function getSingleURLByID($ID, $type = 'single')
{
    $Posts = D('Posts');

    $url_base = U("Post/" . $type);

    if (C('OUR_URL_MODEL') === 'native') {
        $URL = U("Post/" . $type, array('info' => $ID));

    } else {

        C('URL_HTML_SUFFIX', '');
        if (C('OUR_URL_MODEL') === 'post_id') {
            $URL = $url_base . '/' . $ID;
        } else if (C('OUR_URL_MODEL') === 'post_name') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . $posts ['post_name'];
        } else if (C('OUR_URL_MODEL') === 'year/month/post_name') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . $posts ['post_name'];
        } else if (C('OUR_URL_MODEL') === 'year/month/post_id') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . $ID;
        } else if (C('OUR_URL_MODEL') === 'year/post_id') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . $ID;
        } else if (C('OUR_URL_MODEL') === 'year/post_name') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . $posts ['post_name'];
        } else if (C('OUR_URL_MODEL') === 'year/month/day/post_id') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . getTimestamp($posts ['post_date'], 'day') . '/' . $ID;
        } else if (C('OUR_URL_MODEL') === 'year/month/day/post_name') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . getTimestamp($posts ['post_date'], 'day') . '/' . $posts ['post_name'];
        } else {
            $URL = $url_base . '/' . $ID;
        }
    }
    return $URL;
}

// 路由动态获取url
function getTagURLByID($ID)
{
    $Tags = D('Tags', 'Logic');
    if (C('OUR_TAG_MODEL') === 'native') {
        $URL = U('Tag/detail', array("info" => $ID));
    } elseif (C('OUR_TAG_MODEL') === 'ID') {
        $URL = U('/tag') . '/' . $ID;
    } else if (C('OUR_TAG_MODEL') === 'slug') {
        $tag = $Tags->detail($ID);
        $URL = U('/tag') . '/' . $tag ['tag_slug'];
    } else {
        $URL = U('/tag') . '/' . $ID;
    }

    return $URL;
}

function getCatURLByID($ID)
{
    $Tags = D('Cats', 'Logic');
    if (C('OUR_CAT_MODEL') === 'native') {
        $URL = U('Cat/detail', array("info" => $ID));
    } elseif (C('OUR_TAG_MODEL') === 'ID') {
        $URL = U('/cat') . '/' . $ID;
    } else if (C('OUR_TAG_MODEL') === 'slug') {
        $cat = $Tags->detail($ID);
        $URL = U('/cat') . '/' . $cat ['cat_slug'];
    } else {
        $URL = U('/cat') . '/' . $ID;
    }

    return $URL;
}


