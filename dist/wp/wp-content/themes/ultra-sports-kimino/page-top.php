<?php
  /*
  Template Name: TOP
  */

  global $path, $siteURL, $homeURL;
  get_header();
  ob_start();
?>

<section class="mv">
  <div id="lottie"></div>
</section>

<section class="statement">
  <figure class="bg" data-target data-slideup>
    <picture>
      <source srcset="<?php echo $path; ?>/assets/img/top/img_statement_sp.webp" media="(max-width: 759px) and (orientation: portrait)">
      <img src="<?php echo $path; ?>/assets/img/top/img_statement.webp" alt="">
    </picture>
  </figure>
  <div class="catchcopy" data-target data-slideup>WHAT’S BEYOND PLAY?</div>
  <div class="subcopy" data-target data-slideup>Follow your impulses and enjoy the thrill of sports<span data-pc data-tb>.</span><br data-sp>Go beyond with more freedom<span data-pc data-tb>.</span></div>
  <div class="inner">
    <div class="txt">
      <h2 data-target data-slideup>スポーツの先に、何がある？</h2>
      <p data-target data-slideup>スポーツを、考えよう。<br>
        体から届くサインを、<br data-pc data-tb>プレーヤーから届くコンタクトを。<br><br>

        その先には何があるだろう。<br data-pc data-tb>ここは、誰もがスポーツを楽しむだけでなく、<br data-pc data-tb>テクノロジーの力で技術を磨いていける場所。<br data-pc data-tb>AIが動きを見つめ、気づきを与え、<br data-pc data-tb>考える力で背中を押してくれる。<br><br>

        その積み重ねが、<br data-pc data-tb>プレーを想像以上に進化させる。<br><br>

        WHAT’S BEYOND PLAY?<br>
        衝動のままに楽しむスポーツの快感とともに、<br data-pc data-tb>もっと自由に、限界の先へ。</p>
    </div>

    <div class="button" data-target data-slideup>
      <div data-pc data-tb>
        <a class="btn_more" href="<?php echo $siteURL; ?>/about/">MORE</a>
      </div>
    </div>
  </div>
</section>

<div class="page_container">
  <section class="ai_sports">
    <h2 class="sec_title" data-target data-slideup>
      <span class="en">AI x SPORTS</span>
    </h2>

    <div class="contents c1">
      <p class="lead" data-target data-slideup>長い歴史のなかで進化し続けてきたスポーツ。テクノロジーの発展は、その進化をさらに加速させていきます。AIによる解析がそばにあるULTRA SPORTS KIMINOでは、「楽しい！」という本能的な衝動に寄り添いながら、技術と思考が自然と磨かれていく。身体能力とともに、考える力も育てていく時代へ。スポーツはもっと自由に、もっと深く、面白くなる。子どもも、大人も、トップアスリートも。誰もが自分の可能性に出会える、新しいスポーツ体験へ。</p>
    </div>

    <div class="contents c2" data-target data-slideup>
      <div class="unit u1">
        <h3>チームPLAY分析</h3>
        <p>選手が課題を見つけ、それを解決するプロセスを主体的に行うための映像分析ツール《SPLYZA Teams》。自分たちのPLAY映像を見て振り返り、考え、言語化するサイクルを生み出すことで、評価・課題発見・仮説のプロセスを手助けします。</p>
        <figure><img src="<?php echo $path; ?>/assets/img/top/img_ai-sports_1.webp" alt=""></figure>
      </div>

      <div class="unit u2">
        <h3>個人PLAY分析</h3>
        <p>カメラ1台でAIによる３D動作解析が可能なモーションキャプチャアプリ《SPLYZA Motion》。動きのリズムや特徴を解析。体の各部位の角度・速度・特定位置からの距離・脊椎の湾曲角度などを算出。スポーツだけでなく、探究学習や研究、臨床にも活用可能。思っても見なかった自分の癖や習慣に気づくかも。</p>
        <figure><img src="<?php echo $path; ?>/assets/img/top/img_ai-sports_2.webp" alt=""></figure>
      </div>
    </div>

    <div class="contents c3" data-target data-slideup>
      <p>「スポーツは考える力を育む」<br data-sp>自ら課題を発見し、解決する。<br>そのサイクルが成長を促し、<br data-sp>新たな可能性へ。</p>
      <ul class="ai-sports_lists">
        <li>
          <strong>PLAY</strong>
          <span>技術アップ</span>
        </li>

        <li>
          <strong>EDUCATION</strong>
          <span>選手教育</span>
        </li>

        <li>
          <strong>HEALTH</strong>
          <span>健康サポート</span>
        </li>

        <li>
          <strong>CAREER</strong>
          <span>キャリアサポート</span>
        </li>
      </ul>

      <div class="button" data-target data-slideup>
        <a class="btn_more" href="<?php echo $siteURL; ?>/sports/">MORE</a>
      </div>
    </div>
  </section>

  <section class="facility">
    <h2 class="sec_title" data-target data-slideup>
      <span class="en">FACILITY</span>
      <span class="ja">施設案内</span>
    </h2>

    <div class="contents">
      <div class="main">
        <p class="lead" data-target data-slideup>2006年に誕生したスポーツ公園がリニューアルし、ULTRA SPORTS KIMINOが誕生。スポーツを楽しむ全ての人がより一層、躍動できる場を広げていくため、各種スポーツフィールドは勿論、合宿泊所や飲食施設、グッズ販売なども併設。自由に散策したり遊んだりできる山や池に囲まれた緑豊かな自然もそばにあります。年齢やレベル、コンディションにあわせて過ごせる施設・設備・サービスがここにあります。</p>

        <figure class="map" data-target data-slideup><img src="<?php echo $path; ?>/assets/img/top/img_facility_map.webp" alt=""></figure>
      </div>

      <ul class="links">
        <li data-target data-slideup>
          <a href="<?php echo $siteURL; ?>/sports/#facility">
            <div class="name">SPORTS</div>
            <p>個人からチーム、様々なエリアで各種スポーツをプレーができます。個人の筋力トレーニングでのジムから、大会開催まで幅広いスポーツへの対応が可能です。フリースペースとしての機能も持ち、キッズエリアでの子供の遊びや、園路のウォーキングも対応しています。各種スポーツに合わせた機材や道具の貸し出しも行っており、ナイター利用(PM9時まで)も可能です。</p>
          </a>
        </li>

        <li data-target data-slideup>
          <a href="<?php echo $siteURL; ?>/camp-food/">
            <div class="name">CAMP</div>
            <p>個人からチームまで(最大48名)に対応する宿泊所を利用できます。12室の宿泊室はそれぞれ最大4名まで利用でき、シャワー室を完備しています。宿泊の際の食事提供や映像チェックを可能とするミーティングスペース、筋力トレーニングのためのジムを備えた宿泊エリアです。</p>
          </a>
        </li>

        <li data-target data-slideup>
          <a href="<?php echo $siteURL; ?>/camp-food/#food">
            <div class="name">FOOD</div>
            <p>合宿に対応する栄養管理した朝昼晩の食事の提供や、観客や散歩に来られた方用にカフェやスイーツ、ランチの提供も行っています。栄養士監修によるチームオファーに対応した栄養変更メニューも可能です。大会やイベント時の提供調整や各種対応も対応可です。</p>
          </a>
        </li>
      </ul>
    </div>
  </section>

  <section class="access">
    <div class="white_bg"></div>

    <h2 class="sec_title" data-target data-slideup>
      <span class="en">ACCESS</span>
      <span class="ja">アクセス</span>
    </h2>

    <?php echo get_component_with_indent('component/access-contents', 4); ?>
  </section>

  <section class="news">
    <div class="white_bg"></div>

    <div class="inner">
      <h2 class="sec_title" data-target data-slideup>
        <span class="en">NEWS</span>
        <span class="ja">お知らせ</span>
      </h2>

      <div class="contents">
        <?php
          $news = new WP_Query([
            'post_type'      => 'post',
            'posts_per_page' => 3,
          ]);
        ?>
        <?php if($news->have_posts()): ?>

        <ul class="news_lists" data-target data-slideup>
          <?php while ($news->have_posts()) : $news->the_post(); ?>

          <?php echo get_component_with_indent('component/thumb_news', 8); ?>
          <?php endwhile; wp_reset_postdata(); ?>

        </ul>

        <div class="button" data-target data-slideup>
          <a href="<?php echo $siteURL; ?>/news/" class="btn_more">MORE</a>
        </div>
        <?php endif; ?>

      </div>
    </div>

    <?php echo get_component_with_indent('component/reserve-buttons', 4); ?>
  </section>
</div>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_footer(); ?>