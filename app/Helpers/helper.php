<?php
    function sanitizeData ($post = [])
    {
        foreach ($post as $key=>$data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = strip_tags($data);
            $data = htmlspecialchars($data);
            $post[$key] = $data;
        }
        return $post;
    }
?>
