<?php
  /*
  Template Name: CAMP & FOOD
  */

  global $path, $siteURL, $homeURL;
  get_header();
  ob_start();
?>

<header class="page_header">
  <h1 class="page_title">
    <span class="en">CAMP & FOOD</span>
    <span class="ja">合宿 & カフェ</span>
  </h1>
</header>

<div class="page_container">
  <section class="head">
    <div class="contents">
      <p class="lead" data-target data-slideup>食事・ミーティングスペースを完備した合宿施設併設。<br>スポーツチームから教育機関まで、幅広い団体が目的に応じて柔軟に活用できる合宿拠点です。</p>
    </div>
  </section>

  <section class="room">
    <h2 class="sec_title" data-target data-slideup>
      <span class="ja">施設概要</span>
      <span class="en">Room Details</span>
    </h2>

    <div class="contents">
      <p class="lead" data-target data-slideup>部屋：12室（1室最大4名）<br>シャワールーム：男性10室、女性10室<br>（ただし男性or女性のみで占有して20室全使用可）</p>

      <div class="floor" data-target data-slideup>
        <div class="dot_bg">
          <div class="dot_line lt"></div>
          <div class="dot_line lb"></div>
        </div>

        <figure><img src="<?php echo $path; ?>/assets/img/camp-food/img_room.webp" alt=""></figure>

        <div class="txt">
          <dl>
            <dt>部屋数</dt>
            <dd>12室</dd>
          </dl>

          <dl>
            <dt>面積</dt>
            <dd>12㎡</dd>
          </dl>

          <dl>
            <dt>設備 </dt>
            <dd>2段シングルベッド 2台<br>
              2名掛けデスク 2台<br>
              ドライヤー、冷蔵庫<br>
              シャンプー、ハブラシ等のアメニティー</dd>
          </dl>
        </div>
      </div>
    </div>
  </section>

  <section class="rule">
    <h2 class="sec_title" data-target data-slideup>
      <span class="ja">ハウスルール</span>
      <span class="en">House Rules</span>
    </h2>

    <div class="contents" data-target data-slideup>
      <dl>
        <dt>決済方法</dt>
        <dd>
          <p>事前カード決済　※団体ご利用でカード決済が難しい場合、お問い合わせください。</p>
        </dd>
      </dl>

      <dl>
        <dt>チェックイン</dt>
        <dd>
          <p>15：00 - 18：00　※スポーツ合宿泊の場合、荷物のお預かり、アーリーチェックイン可</p>
        </dd>
      </dl>

      <dl>
        <dt>チェックアウト</dt>
        <dd>
          <p>11：00　※スポーツ合宿泊の場合、荷物のお預かり可</p>
        </dd>
      </dl>

      <dl>
        <dt>キャンセルポリシー</dt>
        <dd>
          <p>ご滞在日の13日前以降のキャンセルについては、以下の通りキャンセル料金が発生いたします。<br>
            ・ご滞在の1日前　宿泊料金の100％<br>
            ・ご滞在の5日前　宿泊料金の50％<br>
            ・ご滞在の13日前　宿泊料金の20％<br>
            ・ご滞在の14日前　宿泊料金の0％</p>
        </dd>
      </dl>

      <dl>
        <dt></dt>
        <dd>
          <p>〈喫煙・火気の使用について〉<br>
            周りを木々に囲まれております。安全のため花火を含め火器の使用は固くお断りしております。<br><br>
            〈施設周辺に生息する生き物について〉<br>
            本施設周辺には、多くの場合、人に危害を与えることはございませんが、イノシシ、鹿など野生動物や、マムシなどの危険な生物が生息しています。<br>
            また、夜には蛾や昆虫類が、暖かくなると蜂やムカデが施設内に入ってくることがあります。<br>
            夏場サンダルでの散策は危険なため、靴をお履きになっての散策をお勧めいたします。</p>
        </dd>
      </dl>
    </div>
  </section>

  <section class="fee">
    <h2 class="sec_title" data-target data-slideup>
      <span class="ja">宿泊料金</span>
      <span class="en">Accommodation fee</span>
    </h2>

    <div class="contents" data-target data-slideup>
      <div class="table_area">
        <div class="scroll_area">
          <table>
            <thead>
              <tr>
                <th class="white"></th>
                <th class="yellow"></th>
                <th class="yellow">一般<span>[ 金 / 土 / 祝日前日 ]</span></th>
                <th class="yellow">一般<span>[ その他の曜日 ]</span></th>
                <th class="yellow">高校生以下<span>[ 金 / 土 / 祝日前日 ]</span></th>
                <th class="yellow">高校生以下<span>[ その他の曜日 ]</span></th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td class="yellow">宿泊料金</td>
                <td class="white">1名1泊</td>
                <td class="white">3,700円</td>
                <td class="white">3,100円</td>
                <td class="white">3,000円</td>
                <td class="white">2,500円</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p class="note" data-sp>スクロールできます</p>
      </div>

      <p class="lead">団体の場合は２名以上、飲食(3食)は別途、シャワーは含む。<br>他にも各種プログラムがございます。詳細はHPをご確認ください。</p>
    </div>
  </section>

  <section class="precautions">
    <h2 class="sec_title" data-target data-slideup>
      <span class="ja">注意事項</span>
      <span class="en">Precautions</span>
    </h2>

    <div class="contents">
      <p class="lead">合宿に関する注意事項書</p>

      <ol class="precautions_lists">
        <li data-target data-slideup>
          宿泊契約・予約

          <ul>
            <li>予約は事前に所定の方法で申し込み、利用約款に同意の上で契約成立とします。</li>
            <li>予約のキャンセル・変更は規定の期限内に行い、違約金が発生する場合があります。</li>
            <li>三日前までにご予約をお願いいたします。直前の宿泊は出来ません。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          チェックイン・チェックアウト

          <ul>
            <li>チェックインは通常午後3時から20時まで、チェックアウトは午前10時までです。</li>
            <li>身分証明書の提示をお願いします。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          施設利用上の注意

          <ul>
            <li>宿泊施設内での喫煙は禁止区域を厳守し、喫煙は所定の場所でのみ可能です。</li>
            <li>用具、備品、設備（エアコン、Wi-Fi、照明等）は正しい使用法で扱ってください。損傷の場合は賠償責任が発生します。</li>
            <li>騒音は控え、深夜・早朝の静粛を保つこと。特に体育館利用時間外の音出しには注意が必要です。</li>
            <li>宿泊者以外の立ち入りや無断宿泊は禁止されます。</li>
            <li>チェックアウト前に必ずトイレ、シャワールームの汚れと詰まりを最後確認すること。</li>
            <li>使用時間以外での施設の侵入、使用は安全管理上大変危険であり発覚次第、法的処置を含め厳正に対処します。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          安全・防災

          <ul>
            <li>火災報知器・消火器および非常口の位置を確認し、避難経路は常に確保してください。</li>
            <li>火気使用は厳禁、施設指定の調理場以外での調理は禁止です。</li>
            <li>非常時は施設スタッフの指示に従い速やかに避難してください。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          衛生・ゴミ処理

          <ul>
            <li>ゴミは決められた分類と場所に捨ててください。分別に協力をお願いします。</li>
            <li>共用部分の清掃と衛生保持にご協力ください。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          貴重品・損害賠償

          <ul>
            <li>貴重品はフロントのセーフティボックスに預けてください。紛失盗難は施設は原則責任を負いませんが、申告のないものには保証限度があります。</li>
            <li>故意・過失により施設に損害を与えた場合は賠償責任があります。</li>
            <li>施設内での事故、盗難等に関しまして応急処置や救急車の手配等はいたしますが責任は一切負いません。</li>
          </ul>
        </li>

        <li data-target data-slideup>
          飲食

          <ul>
            <li>施設内での飲食は指定区域のみとし、外部からの飲食物の持ち込みは禁止される場合があります。</li>
          </ul>
        </li>
      </ol>
    </div>
  </section>

  <section class="food">
    <div class="white_bg"></div>

    <h2 class="sec_title" data-target data-slideup>
      <span class="en">FOOD</span>
      <span class="ja">カフェ</span>
    </h2>

    <div class="slide autoscroll" data-target data-slideup>
      <div class="splide">
        <div class="splide__track">
          <div class="splide__list">
            <div class="splide__slide">
              <figure><img src="<?php echo $path; ?>/assets/img/camp-food/img_food_slide_1.webp" alt=""></figure>
            </div>
            <div class="splide__slide">
              <figure><img src="<?php echo $path; ?>/assets/img/camp-food/img_food_slide_2.webp" alt=""></figure>
            </div>
            <div class="splide__slide">
              <figure><img src="<?php echo $path; ?>/assets/img/camp-food/img_food_slide_3.webp" alt=""></figure>
            </div>
          </div>
        </div>
      </div>
      <p>※ 写真はイメージです。メニューは月替わりで変更となります。</p>
    </div>

    <div class="contents">
      <p class="lead" data-target data-slideup>合宿に対応する栄養管理した朝昼晩の食事の提供や、観客や散歩に来られた方用にカフェやスイーツ、ランチの提供も行っています。栄養士監修によるチームオファーに対応した栄養変更メニューも可能です。大会やイベント時の提供調整や各種対応も対応可です。施設利用以外の方もお気軽にどうぞ。</p>

      <div class="detail" data-target data-slideup>
        <dl>
          <dt>営業時間</dt>
          <dd>11：00-17：00</dd>
        </dl>

        <dl>
          <dt>定休日</dt>
          <dd>火曜<br>お盆、年末年始休み</dd>
        </dl>
      </div>

      <div class="row" data-target data-slideup>
        <p>施設利用以外の方もお気軽にどうぞ。</p>
        <a class="btn_pdf" href="<?php echo $path; ?>/assets/pdf/greenby_KIMINO_menu.pdf" target="_blank">
          <span>カフェメニューはこちら</span>
        </a>
      </div>
    </div>

    <div class="buttons" data-target data-slideup>
      <a href="#" target="_blank" class="btn_reserve">
        <span class="en">RESERVE FACILITY</span>
        <span class="ja">施設のご予約はこちら</span>
      </a>

      <a href="#" target="_blank" class="btn_reserve">
        <span class="en">RESERVE CAMP</span>
        <span class="ja">宿泊のご予約はこちら</span>
      </a>
    </div>
  </section>
</div>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_footer(); ?>