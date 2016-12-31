<?php
  /**
   * Plugin Name:        Set ROBOTS
   * Donate link:        https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7994YX29444PA
   * License:            GPL2
   * Version:            1.3.0
   * Description:        Ruthless HTML-Manipulation At A Level Beyond WordPress-API, To Set ROBOTS Meta (+bonus: HTTP-Headers). There Are No Setting - Feel Free To Edit The Values, If You Want To PREVENT ROBOTS-Access, Just Uncomment The Second-Line, And Comment-Out The Line Above It.
   * Author:             eladkarako
   * Author Email:       The_Author_Value_Above@gmail.com
   * Author URI:         http://icompile.eladkarako.com
   * Plugin URI:         https://github.com/eladkarako/wordpress-plugin-raw-html-manipulation-set-robots
   */


/* ╔═════════════════════════════════════════════════════╗
   ║ - Hope You've Enjoyed My Work :]                    ║
   ╟─────────────────────────────────────────────────────╢
   ║ - Feel Free To Modifiy And Distribute it (GPL2).    ║
   ╟─────────────────────────────────────────────────────╢
   ║ - Donations Are A *Nice* Way Of Saying Thank-You :] ║
   ║   But Are NOT Necessary!                            ║
   ║                                                     ║
   ║ I'm Doing It For The Fun Of It :]                   ║
   ║                                                     ║
   ║    - Elad Karako                                    ║
   ║         Tel-Aviv, Israel- July 2016.                ║
   ╚═════════════════════════════════════════════════════╝
░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ */


call_user_func(function () {
  /* ---------------------------------------------------------------------------------------------- */
  $is_engine_ok = call_user_func(function(){
                    function is_server_val($attribute, $regex){
                      return (1 === preg_match($regex, filter_input(INPUT_SERVER,$attribute)));
                    }

                    $is_php     = is_server_val('PHP_SELF',   '#index\.php$#i'   );  /* WordPress-Template-engine */
                    $is_admin   = is_server_val('SCRIPT_URL', '#\/wp\-admin\/#i' );  /* admin folder */
                    $is_feed    = is_server_val('SCRIPT_URL', '#\/feed\/#i'      ) || is_server_val('REDIRECT_SCRIPT_URL',  '#\/feed\/#i'      );
                    $is_atom    = is_server_val('SCRIPT_URL', '#\/atom\/#i'      ) || is_server_val('REDIRECT_SCRIPT_URL',  '#\/atom\/#i'      );
                    $is_json    = is_server_val('SCRIPT_URL', '#\/wp\-json\/#i'  ) || is_server_val('REDIRECT_SCRIPT_URL',  '#\/wp\-json\/#i'  );
                    $is_sitemap = is_server_val('SCRIPT_URL', '#sitemap\.xml$#i' ) || is_server_val('REDIRECT_SCRIPT_URL',  '#sitemap\.xml$#i' );
                    $is_article = is_server_val('SCRIPT_URL', '#\/$#i'           );  /* RISKY! permalink format specific. */
                    
                    return (  true  === $is_php
                           && false === $is_admin
                           && false === $is_feed
                           && false === $is_atom
                           && false === $is_json
                           && false === $is_sitemap
                           && true  === $is_article
                           );
                  });
  if(false === $is_engine_ok) return;
  /* ---------------------------------------------------------------------------------------------- */

/*╔══════════════════════╗
  ║ Modify HTTP-Headers. ║
  ╚══════════════════════╝*/
  header("X-Robots-Tag: archive,follow,imageindex,index,odp,snippet,translate",               true);
/*header("X-Robots-Tag: noarchive,nofollow,noimageindex,noindex,noodp,nosnippet,notranslate", true);*/

/*╔══════════════════╗
  ║ Modify Raw-HTML. ║
  ╚══════════════════╝*/
  add_action('template_redirect', function (){
    @ob_start(function($html){
    /*────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────*/
    /*╔═══════╗
      ║ $html ║
      ╚═══════╝*/
                $html = preg_replace(
                            "#\<\s*meta[^\>]*name\s*=\s*[\"\']robots[\"\'][^\>]*\>#msi"
                          , '<meta name="robots" content="archive,follow,imageindex,index,odp,snippet,translate"/>'
                       /* , '<meta name="robots" content="noarchive,nofollow,noimageindex,noindex,noodp,nosnippet,notranslate"/>' */
                          , $html
                        );
    /*────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────*/
              return $html;
             });
  }, -9999998);

  add_action('shutdown', function () {
    while (ob_get_level() > 0) @ob_end_flush();
  }, +9999998);
});

?>