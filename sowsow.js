//sowsow.js

//===[main.php]===//
$(function () {
  //=[ローディング]=
  $(window).ready(function () {
    $("html").animate({ opacity: "1.0" }, 500);
    setTimeout(function () {
      $("header").animate({ top: "0", opacity: "1.0" }, 600);
    }, 200);
    setTimeout(function () {
      $("#menu-outer").animate({ left: "0%", opacity: "1.0" }, 600);
    }, 500);
    setTimeout(function () {
      $("#menu-containar").slideDown(1000);
    }, 500);
    setTimeout(function () {
      $("#main-container").animate({ opacity: "1.0" }, 1000);
    }, 500);
    setTimeout(function () {
      $("#edit-btn").animate({ opacity: "1.0" }, 1000);
    }, 500);
    setTimeout(function () {
      $("footer").animate({ bottom: "0", opacity: "1.0" }, 600);
    }, 500);
  });
});

$(function () {
  //[メニューアコーディオン]
  //一つの項目だけ開ける
  $(".menu-contents").click(function () {
    $(this).next(".menu-window").slideToggle();
    $(this).toggleClass("open");
    $(".menu-contents").not($(this)).next(".menu-window").slideUp();
    $(".menu-contents").not($(this)).removeClass("open");
    $(".menu-contents").not($(this)).toggleClass("open");
  });
  $("#main-container").click(function () {
    $(".menu-window").slideUp();
  });
});

$(function () {
  //[ボタンanime]
  $(".reg-btn").hover(function () {
    $(this).toggleClass("reg-btn-hover");
  });
});

$(function () {
  //[メニューアコーディオン(mobile)]
  //[サイドウインドウ]
  $("#menu-btn").on("click", function () {
    $(".menu-line").toggleClass("active");
    $("#side-container-m").slideToggle(100);
    $("#side-container-m").toggleClass("active");
    $("#side-container").fadeOut();
    $("#search-window").fadeOut();
    $("#insert-window").fadeOut();
    $("#info-ol").fadeOut();
    $("#logout-ol").fadeOut();
  });

  //[記事検索(mobile)]
  $("#menu-search-m").on("click", function () {
    $("#side-container").fadeIn();
    $("#search-window").fadeIn();
  });
  //[書き込み(mobile)]
  $("#menu-insert-m").on("click", function () {
    $("#side-container").fadeIn();
    $("#insert-window").fadeIn();
  });

  //[メニュークローズボタン(mobile)]
  $(".menu-close-btn").on("click", function () {
    $("#side-container").fadeOut();
    $("#search-window").fadeOut();
    $("#insert-window").fadeOut();
  });

  //[書き込みバリデーション]
  $("#insert-submit").on("click", function () {
    var category = $("#category").val(); //入力(カテゴリ)
    var message = $("#message").val(); //入力(メッセージ)
    //バリデーションチェック(カテゴリ)
    if (category === "0") {
      $("#err-category").text("※カテゴリを選択してください");
      $("#category").focus();
      return false;
    }
    //バリデーションチェック(メッセージ)
    if (message === "") {
      $("#err-message").text("※入力必須です");
      $("#message").focus();
      return false;
    }
  });

  //[カテゴリ入力後にエラー削除]
  $("#category").on("change", function () {
    $("#err-category").text("");
  });
  //[メッセージ入力後にエラー削除]
  $("#message").on("change", function () {
    $("#err-message").text("");
  });

  //[インフォモーダル表示]
  $("#menu-info").on("click", function () {
    $("#info-ol").fadeIn();
  });
  //[インフォモーダル表示(mobile)]
  $("#menu-info-m").on("click", function () {
    $("#info-ol").fadeIn();
  });

  //[背景クリックでメインページへ(info)]
  $("#info-ol").on("click", function () {
    $("#info-ol").fadeOut();
  });

  //[ログアウトモーダル表示]
  $("#logout").on("click", function () {
    $("#logout-ol").fadeIn();
    $("#modal-info").css("display", "flex");
  });
  //[ログアウトモーダル表示(mobile)]
  $("#logout-m").on("click", function () {
    $("#logout-ol").fadeIn();
    $("#modal-info").css("display", "flex");
  });

  //[ログアウト処理]
  $("#close-logout").on("click", function () {
    $("#logout-ol").fadeOut();
  });
});

//===[insert.php]===//
$(function () {
  //[背景クリックでメインへ]
  $("#insert-ol").on("click", function () {
    window.location.href = "../main.php";
  });
});

//===[login.php]===//
$(function () {
  //[ローディング]//
  $(window).ready(function () {
    $("#title-logo").fadeIn(1000);
    setTimeout(function () {
      $("#login-form").animate({ opacity: "1" }, 1000);
    }, 500);
    setTimeout(function () {
      $("#signup-form").animate({ opacity: "1" }, 1000);
    }, 500);
    setTimeout(function () {
      $("#signup-link-outer").animate({ opacity: "1" }, 1000);
    }, 500);
    setTimeout(function () {
      $("footer").animate({ height: "7.0%" }, 1000);
    }, 500);
  });
});

$(function () {
  //=[ログインフォーム]=//
  const login_id = $("#login-id");
  const login_pass = $("#login-pass");

  //[バリデーションチェック(ログイン)]
  $("#login-submit").on("click", function () {
    if ($("#login-id").val() == "") {
      $("#login-err").text("IDを入力してください");
      login_id.css("border", "0.2rem solid red");
      return false;
    }
    if ($("#login-pass").val() == "") {
      $("#login-err").text("パスワードを入力してください");
      $("#login-pass-outer").css("border", "0.2rem solid red");
      return false;
    }
    const pass_match = login_pass.match([A - Za - z0 - 9]);
    if ((pass_match = null)) {
      $("#login-err").text("半角英数字で４～８字で入力してください");
      return false;
    }
  });

  //[ID入力後にエラー削除]
  $("#login-id").on("change", function () {
    if ($("#login-id").val() != "") {
      $("#login-err").text("");
      login_id.css("border", "0.2rem solid black");
    }
  });

  //[パスワード入力後にエラー削除]
  $("#login-pass").on("change", function () {
    if ($("#login-pass").val() != "") {
      $("#login-err").text("");
      $("#login-pass-outer").css("border", "0.2rem solid black");
    }
  });

  //[パスワード表示切替(ログイン)]
  $("#pass-eye").on("click", function () {
    $(this).attr("src", "images/pass_eye_none.jpg");
    if ($(this).prop("checked")) {
      login_pass.attr("type", "text");
      $("#pass-eye-img").attr("src", "../images/pass_eye_none.png");
    } else {
      login_pass.attr("type", "password");
      $("#pass-eye-img").attr("src", "../images/pass_eye.png");
    }
  });

  //[新規登録表示]
  $("#signup-link-outer").on("click", function () {
    $("#signup-ol").fadeIn();
    $("#login-form").fadeOut();
    $("#signup-link-outer").fadeOut();
  });
});

$(function () {
  //=[新規登録フォーム]=//
  const signup_id = $("#signup-id");
  const signup_pass = $("#signup-pass");

  //[バリデーションチェック(新規登録)]
  $("#signup-submit").on("click", function () {
    if ($("#signup-id").val() == "") {
      $("#signup-err").text("※ IDを入力してください");
      signup_id.css("border", "0.2rem solid red");
      return false;
    }
    if (signup_pass.val() == "") {
      $("#signup-err").text("※ パスワードを入力してください");
      $("#signup-pass-outer").css("border", "0.2rem solid red");
      return false;
    }
  });

  //[ID入力後にエラー削除]
  signup_id.on("change", function () {
    if ($("#signup-id").val() != "") {
      $("#signup-err").text("");
      signup_id.css("border", "0.2rem solid black");
    }
  });
  //[パスワード入力後にエラー削除]
  signup_pass.on("change", function () {
    if ($("#signup-pass").val() != "") {
      $("#signup-err").text("");
      $("#signup-pass-outer").css("border", "0.2rem solid black");
    }
  });

  //[パスワード表示切替(新規登録)]
  $("#pass-eye").on("click", function () {
    $(this).attr("src", "images/pass_eye_none.jpg");
    if ($(this).prop("checked")) {
      signup_pass.attr("type", "text");
      $("#signup-pass-eye-img").attr("src", "../images/pass_eye_none.png");
    } else {
      signup_pass.attr("type", "password");
      $("#signup-pass-eye-img").attr("src", "../images/pass_eye.png");
    }
  });

  //[ログインへ]
  $("#return-login").on("click", function () {
    $("#signup-ol").fadeOut();
    $("#login-form").fadeIn();
    $("#signup-link-outer").fadeIn();
  });
});

//===[logout.php]===//
$(function () {
  //=[ログインフォーム]=//
  $(window).ready(function () {
    $("#logout-center-line").addClass("line-spread");
    setTimeout(function () {
      $("#logout-item-up").addClass("item-up"), 500;
    }, 800);
    setTimeout(function () {
      $("#logout-item-down").addClass("item-down"), 500;
    }, 800);
  });
});

$(function () {
  //[背景クリックでログインへ]
  $("#logout-container").on("click", function () {
    window.location.href = "login.php";
  });
});

//===[user-edit.php]===//
$(function () {
  //=[メニュークローズ]=//
  $("#close-btn-edit").on("click", function () {
    $("#side-container-m").fadeOut();
  });
});
