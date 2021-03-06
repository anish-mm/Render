# Render
Creating renderers for render arrays of type link, table, basic page with title,
 body and tags, etc.

## RENDER ARRAY EXAMPLES

## EXAMPLE RENDER ARRAY FOR A TABLE
```
$table = array(
    'type' => 'table',
    
    // list of table headers
    // an array of strings
    'headers' => array(
        'col_name_1',
        'col_name_2',
        'col_name_3',
        ...
    ),
    
    // an array of arrays representing rows
    // render arrays for tables or links
    'rows' => array(
        
        // a row may be array of strings
        array(
            'row_1_cell_1',
            'row_1_cell_2',
            ...
        ),
        
        // a row may be array of render arrays for links and tables
        array(
            $render_array_for_link,
            $render_array_for_table,
            ...
        ),
        
        // a row may be an array of both render arrays and strings 
        array(
            $render_array_for_link,
            'row_2_cell_2',
            $render_array_for_table,
            ...
        ),
        ...
    ),
);
```

## Notes

For a table, number of headers in taken as number of columns, and if a row 
contains more cell values, the excess ones at the end are discarded.

-----

## EXAMPLE RENDER ARRAY FOR A LINK
```
$link = array(
    'type' => 'link',
    
    //string containing the url to be linked
    'url' => $url_to_my_page,
    
    //string containing the link text
    'text' => $link_text,
);
```
## Notes

Link text by default is 'Click Here'.

-----

## EXAMPLE RENDER ARRAY FOR A PAGE
```
$page = array(
    'type' => 'page',
    
    //string containing title of page
    'title' => $title_for_my_page,
    
    //string OR render array for link or table OR
    // array of strings and render arrays for links and tables
    'body' => array(
        $render_array_for_table,
        $render_array_for_link,
        "this is an example",
        ...
    ),
    
    //array of link render arrays
    'tags' => array(
        $tag_1_link,
        $tag_2_link,
        $tag_3_link,
        ...
    ),
);
```

## Notes

Tags are optional for a page.

-----
