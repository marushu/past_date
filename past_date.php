<?php $posts = get_posts(array('post_type' => 'one_line_comment', 'taxonomy' => 'span', 'posts_per_page' => 5, 'order' => 'ASC')); foreach($posts as $post) : setup_postdata($post); //1行コメントの改訂版 ?>
	<?php
			$key = "表示期間"; //カスタムフィールドの項目名（label）
			$value = get_post_meta($post->ID, $key, true); //カスタムフィールドに入れた日数を格納
			//↑↑ココまでがカスタムフィールドの項目を出力する部分
			$value_unix = $value * 24 * 60 * 60;
			//▼ここで日付を整形。sbustrで最初の文字から○文字を抜き出し（一文字目は「0（ゼロ）」）、str_replaceで置き換えたい文字と置き換える文字を指定。
			$toukoubi = str_replace("-", "/", substr($post->post_date, 0, 10)); //投稿日の日付 YYYY/mmmm/dddd
			$last_modified_day = str_replace("-", "/", substr($post->post_modified, 0, 10)); //最終編集日の日付 YYYY/mmmm/dddd
			//▲ここで日付を整形。sbustrで最初の文字から○文字を抜き出し（一文字目は「0（ゼロ）」）、str_replaceで置き換えたい文字と置き換える文字を指定。
			//投稿日をUNIXタイムに変形
			$toukoubi_unix = strtotime($toukoubi); 
			//投稿期限日のUNIXタイムを求める
			$toukou_limit_unix = $toukoubi_unix + $value_unix;
			$today_unix = date('U', current_time( 'timestamp' ));
			//投稿期限日から今日を減算。これで今投稿してから何日経過したかが判明する
			$past_days_unix = $toukou_limit_unix - $today_unix;
			$past_day = ceil($past_days_unix / (24*60*60));  //ceilを使って端数を切り上げる。
			$last_modified_day_mysql = $post->post_modified;
		?>
	<?php if($past_days_unix > 0): ?>
		<div class="one_line">
			<p><?php the_title(); ?></p>
		<!-- #one_line end --></div>
	<?php endif; ?>
<?php endforeach; ?>