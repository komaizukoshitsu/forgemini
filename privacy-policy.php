<?php
/*
Template Name: Privacy Policy
*/
get_header(); ?>
<main data-barba="container" data-barba-namespace="page">
    <div class="w-[90%] xl:max-w-180 mx-auto mt-16">
        <article class="">
            <header class="">
                <h1 class="text-center lg:text-left text-[32px] lg:text-[45px] font-garamond italic tracking-[0.15em] leading-[1.25]">Privacy Policy</h1>
            </header>

            <div class="mt-7 lg:mt-[84px] flex flex-col lg:flex-row">
                <div class="w-full lg:w-75 lg:flex-shrink-0">
                    <?php
                        get_template_part('template-parts/heading-with-brackets', null, [
                            'heading_text' => '個人情報保護方針',
                            'heading_tag'  => 'h2',
                        ]);
                    ?>
                </div>
                <div class="w-full lg:flex-1">
                    <p class="tracking-wide mt-5 lg:mt-0 text-sm lg:text-base leading-[1.75]">
                        本サイトおよび本サイトを運営する事業者、株式会社いぬの絵（以下「当社」といいます）は、お客様のプライバシーを保護し、尊重いたします。このプライバシーポリシー（以下「本ポリシー」といいます）は、本サイトをご利用いただく際に、お客様の個人データがどのように扱われるかについて明示しています。本サイトをご利用いただくことで、お客様は本ポリシーに同意したものとみなされます。
                    </p>
                </div>
            </div>
            <div class="mt-10 flex flex-col lg:flex-row">
                <div class="w-full lg:w-75 lg:flex-shrink-0">
                    <?php
                        get_template_part('template-parts/heading-with-brackets', null, [
                            'heading_text' => '個人情報とは',
                            'heading_tag'  => 'h2',
                        ]);
                    ?>
                </div>
                <div class="w-full lg:flex-1">
                    <p class="tracking-wide mt-5 lg:mt-0 text-sm lg:text-base leading-[1.75]">
                        本ポリシーにおいて、個人情報とは個人に関する情報を意味します。具体的には、氏名、住所、電話番号、メールアドレス等、特定の個人を識別できるものを指します。
                    </p>
                </div>
            </div>
            <div class="mt-10 flex flex-col lg:flex-row">
                <div class="w-full lg:w-75 lg:flex-shrink-0">
                    <?php
                        get_template_part('template-parts/heading-with-brackets', null, [
                            'heading_text' => '個人情報の管理',
                            'heading_tag'  => 'h2',
                        ]);
                    ?>
                </div>
                <div class="w-full lg:flex-1">
                    <p class="tracking-wide mt-5 lg:mt-0 text-sm lg:text-base leading-[1.75]">
                        本サイト経由でお預かりした個人情報は、不正アクセス、紛失、漏えい等が起こらないよう、慎重かつ適切に管理いたします。
                    </p>
                </div>
            </div>
            <div class="mt-10 flex flex-col lg:flex-row">
                <div class="w-full lg:w-75 lg:flex-shrink-0">
                    <?php
                        get_template_part('template-parts/heading-with-brackets', null, [
                            'heading_text' => '個人情報の利用目的',
                            'heading_tag'  => 'h2',
                        ]);
                    ?>
                </div>
                <div class="w-full lg:flex-1">
                    <p class="tracking-wide mt-5 lg:mt-0 text-sm lg:text-base leading-[1.75]">
                        本サイトのお問い合わせやサービスへのお申し込み等を通じてお預かりした個人情報は、以下に示す利用目的のために適正に利用いたします。
                        <ul class="tracking-wide list-disc pl-5 text-[14px] lg:text-base" style="line-height:1.75">
                            <li>お客様からのお問い合わせ等に対応するため</li>
                            <li>本サイトのサービス向上・改善や新サービスを検討するための分析等を行うため</li>
                            <li>個人を識別できない形で統計データを作成し、本サイトおよびお客様の参考資料とするため</li>
                        </ul>
                    </p>
                </div>
            </div>
            <div class="mt-10 flex flex-col lg:flex-row">
                <div class="w-full lg:w-75 lg:flex-shrink-0">
                    <?php
                        get_template_part('template-parts/heading-with-brackets', null, [
                            'heading_text' => '個人情報の第三者提供',
                            'heading_tag'  => 'h2',
                        ]);
                    ?>
                </div>
                <div class="lg:w-[calc(100%-290px)]">
                    <ul class="tracking-wide mt-5 lg:mt-0 text-sm lg:text-base leading-[1.75]">
                        <li>当社は、本人から個人情報が利用目的の範囲を超えて取り扱われている、または不正な手段により取得されたものであるという理由で、その利用の停止または消去（以下「利用停止等」といいます）を求められた場合には、遅滞なく必要な調査を行います</li>
                        <li>前項の調査結果に基づき、その請求に応じる必要があると判断した場合には、遅滞なく、当該個人情報の利用停止等を行います</li>
                        <li>当社は、前項の規定に基づき利用停止等を行った場合、または利用停止等を行わない旨の決定をしたときは、遅滞なく、これをユーザーに通知します</li>
                        <li>利用停止等に多額の費用を有する場合、その他利用停止等を行うことが困難な場合であって、ユーザーの権利利益を保護するために必要なこれに代わるべき措置をとれる場合は、この代替策を講じるものとします</li>
                    </ul>
                </div>
            </div>
            <div class="mt-10 flex flex-col lg:flex-row">
                <div class="w-full lg:w-75 lg:flex-shrink-0">
                    <?php
                        get_template_part('template-parts/heading-with-brackets', null, [
                            'heading_text' => 'お問い合わせ',
                            'heading_tag'  => 'h2',
                        ]);
                    ?>
                </div>
                <div class="lg:w-[calc(100%-290px)]">
                    <p class="tracking-wide mt-5 lg:mt-0 text-sm lg:text-base leading-[1.75]">
                        本ポリシーに関するお問い合わせは、<a href="<?php bloginfo('url');?>/contact" class="contact-link">お問い合わせフォーム</a>にご記入ください。
                    </p>
                </div>
            </div>
            <div class="mt-10 text-right text-xs lg:text-base" style="line-height:1.75">2024年00月00日制定</div>
        </article>

    </div>
</main>

<?php get_footer(); ?>
