<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

<?php $i = 0; // 5번째 이미지 체크 변수 ?>

<?php if ( have_posts() ) : ?>

	<header class="page-header">
		<h1>
			<?php if ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives: %s', 'twentyeleven' ), '<span>' . get_the_date() . '</span>' ); ?>
	
			<?php elseif ( is_month() ) : ?>
				<?php printf( __( '<span class="white_font">%s</span>에 읽은 도서 목록', 'twentyeleven' ), '<span>' . get_the_date( 'Y년 m월' ) . '</span>' ); ?>
	
			<?php elseif ( is_year() ) : ?>
				<?php printf( __( '<span class="white_font">%s</span>에 읽은 도서 목록', 'twentyeleven' ), '<span>' . get_the_date( 'Y년' ) . '</span>' ); ?>
	
			<?php elseif ( is_tax('book_author') ) : ?>
				<?php  echo '내가 읽은 <span class="white_font">' . 
				strip_tags(get_book_author($post->ID)) . '</span>의 도서'; ?>
	
			<?php elseif ( is_tax('publisher') ) : ?>
				<?php  echo '내가 읽은 <span class="white_font">' . 
				strip_tags(get_book_publisher($post->ID)) . '</span>의 도서'; ?>
	
			<?php else : ?>
	
			<?php endif; ?>
		</h1>
	</header>

	<?php /* Start the Loop */ ?>
	
	<?php while ( have_posts() ) : the_post(); ?>

		<?php
			/* Include the Post-Format-specific template for the content.
			 * If you want to overload this in a child theme then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			if (++$i % 5 == 0) :
				get_template_part( 'content-fifth-book', get_post_format() );
			else :
				get_template_part( 'content', get_post_format() );
			endif;

		?>

	<?php endwhile; ?>

	<!-- 위키백과의 저자정보 출력 -->
	<?php $author = strip_tags(get_book_author($post->ID)); ?>
	<input type="hidden" id="author" value="<?php echo $author; ?>" />
	<input type="hidden" id="theme-path" value="<?php echo get_template_directory_uri(); ?>" />
	<div id="author_wiki"></div>

	<script type="text/javascript">
		$(window).load(function() {
			var path = $("#theme-path").val();
			var author = $("#author").val();
			
			// 로딩중 이미지를 표시한다.
			$("#author_wiki").append('<img src="'+path+'/images/loading.gif" />');
			$("#author_wiki").attr("class", "author-info well well-small");
			
			// 위키정보를 요청한다.
			path = path+"/ajax/get-wiki-content.php?keyword="+author;
			$.get(path, function(data, status) {
				// 로딩중 이미지를 숨긴다.
				$("#author_wiki img").css("display", "none");

				// 데이터가 없는 경우 div를 숨긴다.
				if (data == "") {
					$("#author_wiki").css("display", "none");
					return;
				}

				// 데이터를 출력하다.
				var wiki_link = "http://ko.wikipedia.org/wiki/" + author;
				$("#author_wiki").append("<h1>"+ author +"</h1>");
				$("#author_wiki").append("<p>"+ data +
					' <a href="'+ wiki_link +
					'"><span class="label">위키백과</span></a></p>');
			});
			
		});
	</script>

	<?php my_nav(); ?>

<?php else : ?>

<?php endif; ?>

<?php get_footer(); ?>
