<?php
  /*
  Template Name: ACCESS
  */

  global $path, $siteURL, $homeURL;
  get_header();
  ob_start();
?>

<header class="page_header">
  <h1 class="page_title">
    <span class="en">ACCESS</span>
    <span class="ja">アクセス</span>
  </h1>
</header>

<div class="gmap" data-target data-slideup>
  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3300.878615502266!2d135.3084489!3d34.175021!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6007362fd12b8cf9%3A0x1efc5bfd9e6fb7a3!2z57SA576O6YeO55S656uL44K544Od44O844OE5pa96Kit44K544Od44O844OE5YWs5ZyS566h55CG5qOf!5e0!3m2!1sja!2sjp!4v1770703053050!5m2!1sja!2sjp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>

<div class="page_container">
  <section class="access">
    <h2 class="sec_title" data-target data-slideup>
      <span class="en">ACCESS</span>
      <span class="ja">アクセス</span>
    </h2>

    <div class="contents">
      <figure class="map" data-target data-slideup><img src="<?php echo $path; ?>/assets/img/access/img_access_map.webp" alt=""></figure>

      <div class="txt" data-target data-slideup>
        <dl>
          <dt>ADDRESS</dt>
          <dd>
            <p>〒640-1141<br>
              和歌山県海草郡紀美野町動木518<br>
              073-489-5368<br>
              営業時間：9:00-21:00　定休日：火曜日</p>
            <p>
              <a href="https://maps.app.goo.gl/khDJdZaBeAuyW6QU7" target="_blank" class="pin">Google Maps</a>
            </p>
          </dd>
        </dl>

        <dl>
          <dt>TRAIN</dt>
          <dd>
            <p>JRきのくに線「海南駅」下車、<br>オレンジバス「大成校舎前」下車、徒歩20分</p>
          </dd>
        </dl>

        <dl>
          <dt>CAR</dt>
          <dd>
            <p>阪和自動車道「海南東IC」から車で約25分<br>駐車場：150台</p>
          </dd>
        </dl>
      </div>
    </div>
  </section>

  <section class="precautions">
    <h2 class="sec_title" data-target data-slideup>
      <span class="ja">注意事項</span>
      <span class="en">Precautions</span>
    </h2>

    <div class="contents">
      <ol class="precautions_lists">
        <li data-target data-slideup>
          利用資格と申込手続き

          <ul>
            <li>利用申込は原則として代表者が行い、連絡先・所属団体名・目的・担当者情報を正確に記載してください。</li>
            <li>申込受付後、施設管理者から利用申請内容の確認連絡を必ず受けてください。</li>
            <li>営利目的の利用は事前申し出が必要で、別料金や条件を設定する場合があります。</li>
            <li>18歳未満は保護者の同意書が必要です。</li>
            <li>施設内における盗難等は一切責任を負いません。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          利用時間・管理責任

          <ul>
            <li>利用時間の15分前に集合し、施設管理者が施錠や点検を行います。</li>
            <li>利用終了後は速やかに退出し、施錠や清掃を担当者の責任で確実に行うこと。</li>
            <li>延長利用は原則禁止で、事前に申請し承認を得なければなりません。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          施設内での行動規範

          <ul>
            <li>施設内での喫煙は所定の場所以外禁止。</li>
            <li>飲酒は厳禁。アルコールによる問題が発生した場合は即座に利用中止となります。</li>
            <li>他の利用者や近隣住民への迷惑行為（大声、騒音、乱暴な行動）は禁止。</li>
            <li>ペットの同伴禁止（盲導犬等配慮対象を除く）。</li>
            <li>ゴミは利用者が持ち帰るか、指定の分別ルールに従って処理してください。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          設備・用具の詳細管理

          <ul>
            <li>施設設備（照明、スコアボード、ネットなど）は適切に使用し、勝手な操作や改造は禁止。</li>
            <li>備品の貸出は原則として管理者の許可のもと、使用後は元の場所に戻し点検を行う。</li>
            <li>備品を破損・紛失した場合は速やかに報告し、弁償または修理費を負担する。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          安全対策・応急処置

          <ul>
            <li>利用者は事前に安全ルールの説明を受け、同意することが必須。</li>
            <li>怪我や事故が発生した場合は、まず応急処置を行い、速やかに施設管理者に報告すること。</li>
            <li>救急車通報の際は代表者が責任を持って対応し、事故報告書を作成してください。</li>
            <li>緊急避難経路や消火器の場所を利用前に確認してください。</li>
            <li>施設内での事故、怪我につきましては応急処置や救急車の手配などは行いますがそれらの責任は一切負いかねます。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          天候等特別条件の対応

          <ul>
            <li>屋外施設は天候や地面状態によって利用中止や制限がかかる場合があります。</li>
            <li>台風・豪雨など自然災害時は管理者の指示に従い、無理に使用しないこと。</li>
            <li>使用当日の天候判断は管理者が行い、利用者へ速やかに連絡します。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          料金・支払い・キャンセル規定

          <ul>
            <li>使用料金は事前支払いが原則で、未払いの場合は利用を拒否します。</li>
            <li>キャンセル料発生の詳細を明示し、当日キャンセルは全額負担の場合があります。</li>
            <li>予約の代理キャンセルは認めません。必ず代表者本人または連絡担当者が行うこと。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          個人情報の取扱いと連絡体制

          <ul>
            <li>利用者情報は施設運営の目的以外には使用しません。</li>
            <li>緊急連絡先は必ず最新の情報を提出し、異動時は速やかに連絡義務あり。</li>
            <li>イベント等で記録撮影を行う場合は、利用者に事前に通知し同意を得ること。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          禁止事項の具体例

          <ul>
            <li>施設内での商業宣伝、募金活動、政治活動は禁止。</li>
            <li>火気、花火、バーベキュー等火を使う行為は所定のエリアでのみ許可。</li>
            <li>施設や設備の持ち出し、勝手な改造は禁止。</li>
            <li>騒音により近隣苦情があった場合は即時利用中止の措置をとる。</li>
          </ul>
        </li>
      </ol>
    </div>
  </section>
</div>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_footer(); ?>