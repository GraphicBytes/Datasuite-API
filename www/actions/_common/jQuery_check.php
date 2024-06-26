document.addEventListener("DOMContentLoaded", function() {

      if (!window.$) {
        let script = document.createElement('script');
        document.head.appendChild(script);
        script.type = 'text/javascript';
        script.src = "https://code.jquery.com/jquery-3.6.0.min.js";
        script.onload = P_PAT_RUN;

        console.log("jQuery Loaded");

      } else {

        console.log(typeof $());

        P_PAT_RUN();

        console.log("jQuery Already Available");
      }

});
