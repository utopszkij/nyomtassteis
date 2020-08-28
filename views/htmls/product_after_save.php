			<h2>Most jelenik meg a google map térkép a rajta lévő szerkeszthető sokszöggel és kereső mezővel</h2>
			<p>product_id= <?php echo $this->controller->product_id; ?></p>
			<p>Ellenörizni: csak bejelentkezett admin használhatja!</p>
			<p>Vizsgálni kell a product ACF "is_area" értékét, ha ez false akkor nem kell csinálni semmit.</p>
			<p></p>			
			<p>a sokszög kialakítása után a "Rendben" vagy a "Mégsem" gombra kell /lehet kattintani.</p>
			<p>"Rendben" esetén tárol, Mindkét esetben visszatérünk a product editor képernyőre.</p>
			<a href="<?php echo get_site_url(); ?>/wp-admin/post.php?post=<?php echo $this->controller->product_id; ?>&action=edit">Rendben</a>
			&nbsp;
			<a href="<?php echo get_site_url(); ?>/wp-admin/post.php?post=<?php echo $this->controller->product_id; ?>&action=edit">Mégsem</a>
