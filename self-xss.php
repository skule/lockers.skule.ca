<?php
  session_start();
  include 'navbar.php';
?>

  <style>

  table {
    float: left;
  }
  tr, td {
    margin:0 !important;
    height:2em !important;
    padding: 0px !important;
  }

  .collapsible li i {
    -ms-transform: rotate(90deg); /* IE 9 */
    -webkit-transform: rotate(90deg); /* Chrome, Safari, Opera */
    transform: rotate(90deg);
  }

  .collapsible li.active i {
    -ms-transform: rotate(180deg); /* IE 9 */
    -webkit-transform: rotate(180deg); /* Chrome, Safari, Opera */
    transform: rotate(180deg);
  }

  </style>

  <main>
    <div class="row container">
      <div>

        <!--INFO-->
        <ul class="collection with-header z-depth-0">
          <li class="collection-header transparent black-text">
            <h2>What is Self-XSS?</h2>
          </li>
          <li class="collection-item">
            <p>
              Self-XSS is a type of internet forgery where the attacker tells the victim (that's you) that you will be able to do some special thing if you paste a given text into your web browser developer console. In this case, the attacker may have told you that you will get access to a locker at a reduced or no fee.
            </p>
            <p>
              Your web browser has built-in mechanisms that keep other users or websites from accessing the information on this page. This includes any private information we may be displaying to you, along with the payment information you provide to PayPal through our website. These mechanisms, however, cannot protect you from the attackers if you paste malicious code into your browser. The malicious code given to you by the attacker may disable these mechanisms and let the attacker steal your payment information.
            </p>
            <p>
              <b>To keep this from happening, do not paste anything given to you by someone else in your browser.</b> It is highly unlikely that an internet stranger is simply being kind to you -they likely want to steal your money. Also keep in mind that <b>even if you receive the code from one of your friends, they might have been hacked themselves.</b> A very common way to hack people is to use already-comprimised accounts of their friends.
            </p>
            <p>Dont fall victim to Self-XSS attacks, don't become a statistic.</p>
          </li>
        </ul>
      </div>

    </div>
  </main>

  <?php
    include 'footer.php';
  ?>
