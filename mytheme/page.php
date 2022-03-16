<?php get_header(); ?>
<main id="content" role="main">

<header class="header">
<h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1> <?php edit_post_link(); ?>
</header>
<div class="entry-content" itemprop="mainContentOfPage">

<?php the_content(); ?>

<?php 

if ( !is_user_logged_in() ) {
	echo '<h3>Форма ввода доступна только авторизованным пользователям</h3>';
}
else {
	echo 'Вы авторизованы';

    $terms = get_terms( [
        'taxonomy' => 'bet_type',
        'hide_empty' => false,
    ] );

    echo '
        <div class="form-container">

            <H3>Форма ввода вставки</H3>
            
            <form id="betForm" action="" method="post" name="betForm">
            
                <label for="title">Название ставки</label>
                <input type="text" name="title" id="title" placeholder="Введите название"> 
                
                <label for="description">Описание ставки</label>
                <textarea  name="description" id="description" placeholder="Введите описание"></textarea> 
                
                <label for="bet_type">Тип ставки</label>
                <select name="bet_type" id="bet_type">
                    <option value="" disabled selected>Выберите тип ставки</option>
        ';
        foreach ($terms as $term) {
            echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';  
        }
        echo '
            </select>
            <input type="hidden" name="action" value="send_form" style="display: none; visibility: hidden; opacity: 0;">
            <button type="submit" id="submit" class="go">Отправить</button>
            </div>
        </form>
        </div>';
}

?>

<style>
h3 {
    font-size: 22px;
    margin: 20px 20px 20px 0px;
}

.form-container {
    margin: 20px;
}

#betForm {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 300px;
}

.validation-error {
    color: red;
}

</style>

<div class="entry-links"><?php wp_link_pages(); ?></div>
</div>

</main>
<?php get_footer(); ?>

