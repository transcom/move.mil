import { group, sleep } from 'k6';
import http from 'k6/http';

export let options = {
  maxRedirects: 0,
};

export default function() {

  group("page_1 - https://test.move.mil/", function() {
    let req, res;
    req = [{
      "method": "get",
      "url": "https://test.move.mil/",
      "params": {
        "headers": {
          "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "Upgrade-Insecure-Requests": "1",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
      "params": {
        "headers": {
          "Accept": "text/css,*/*;q=0.1",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
      "params": {
        "headers": {
          "Accept": "text/css,*/*;q=0.1",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/favicons/favicon-57.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-dot-gov.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-https.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/ustranscom-logo.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/close.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/united-states-department-of-the-army-emblem.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/images/icon--plan-your-move.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/images/icon--schedule-your-move.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/images/icon--get-ready-for-moving-day.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/images/icon--settling-in.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/planyourmove.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/movingtruckhero.jpg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/getready.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/settlingin.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/scheduleyourmove.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/united-states-marine-corps-emblem.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/united-states-department-of-the-navy-emblem.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/united-states-department-of-the-air-force-emblem.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/united-states-coast-guard-emblem.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/js/js_AhbPR7vrUdq0UnLoU8TWkSvVlP4ScOUU8tYbORgwWcM.js",
      "params": {
        "headers": {
          "Accept": "*/*",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/angle-arrow-down-primary.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/modules/contrib/extlink/extlink_s.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    }];
    res = http.batch(req);
    sleep(6.03);
  });
  group("page_2 - https://test.move.mil/moving-guide", function() {
    let req, res;
    req = [{
      "method": "get",
      "url": "https://test.move.mil/moving-guide",
      "params": {
        "headers": {
          "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "Upgrade-Insecure-Requests": "1",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
      "params": {
        "headers": {
          "Referer": "https://test.move.mil/moving-guide",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
      "params": {
        "headers": {
          "Referer": "https://test.move.mil/moving-guide",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/favicons/favicon-57.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-dot-gov.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-https.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/ustranscom-logo.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/close.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/conus.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/moving-guide",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/oconus.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/moving-guide",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/hhg_0.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/moving-guide",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/ppm.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/moving-guide",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/js/js_AhbPR7vrUdq0UnLoU8TWkSvVlP4ScOUU8tYbORgwWcM.js",
      "params": {
        "headers": {
          "Accept": "*/*",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://www.move.mil/assets/conus-0d2ae73b2f8fcc6de8be2d48996c24ac12c92e2b79f5d57e9300bd9ec105db10.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/moving-guide",
          "Host": "www.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/unaccompaniedbaggage.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/moving-guide",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/automobiles.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/moving-guide",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/nontempstorage.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/moving-guide",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://www.move.mil/assets/oconus-b2d3054d693524431c8511c902fecbb50e9263307307fd0e36d7954f0e3ee2ba.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/moving-guide",
          "Host": "www.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/angle-arrow-down-primary.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/modules/contrib/extlink/extlink_s.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-italic-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon--pro-tip.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    }];
    res = http.batch(req);
    sleep(6.18);
  });
  group("page_3 - https://test.move.mil/entitlements", function() {
    let req, res;
    req = [{
      "method": "get",
      "url": "https://test.move.mil/entitlements",
      "params": {
        "headers": {
          "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "Upgrade-Insecure-Requests": "1",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css_Sq-T3qhCAGh6e4bwOEMnG4NhIW5PhaiEbaVmLi-JFY0.css?pb03ed",
      "params": {
        "headers": {
          "Accept": "text/css,*/*;q=0.1",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
      "params": {
        "headers": {
          "Referer": "https://test.move.mil/entitlements",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/favicons/favicon-57.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-dot-gov.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/boats_2.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/ustranscom-logo.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/close.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/animals_0.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/firearms_0.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/alcohol_1.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/consumable_0.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/appliances_0.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/televisions_0.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/lawnfurniture_1.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/cars_0.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/motorcycles_0.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/spareparts_1.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-https.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/miscsportinggoods_0.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/utilitytrailer_0.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/mobilehome_1.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/boats_3.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/js/js_KF9rx_b0-3MFvs8rJ9p2QolVzoWJwgDe1gA2_mf5JyU.js",
      "params": {
        "headers": {
          "Accept": "*/*",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-italic-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/angle-arrow-down-primary.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/alerts/info.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/alerts/warning.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/parser/entitlements",
      "params": {
        "headers": {
          "Accept": "application/json, text/plain, */*",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/entitlements",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/modules/contrib/extlink/extlink_s.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/arrow-both.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/arrow-both.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    }];
    res = http.batch(req);
    sleep(6.04);
  });
  group("page_4 - https://test.move.mil/moving-guide/conus", function() {
    let req, res;
    req = [{
      "method": "get",
      "url": "https://test.move.mil/moving-guide/conus",
      "params": {
        "headers": {
          "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "Upgrade-Insecure-Requests": "1",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
      "params": {
        "headers": {
          "Referer": "https://test.move.mil/moving-guide/conus",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
      "params": {
        "headers": {
          "Referer": "https://test.move.mil/moving-guide/conus",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/favicons/favicon-57.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/js/js_AhbPR7vrUdq0UnLoU8TWkSvVlP4ScOUU8tYbORgwWcM.js",
      "params": {
        "headers": {
          "Referer": "https://test.move.mil/moving-guide/conus",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-dot-gov.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-https.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/ustranscom-logo.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/close.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon--pro-tip.svg",
      "params": {
        "headers": {
          "Referer": "https://test.move.mil/moving-guide/conus",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-italic-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/angle-arrow-down-primary.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/modules/contrib/extlink/extlink_s.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    }];
    res = http.batch(req);
    sleep(6.06);
  });
  group("page_5 - https://test.move.mil/faqs", function() {
    let req, res;
    req = [{
      "method": "get",
      "url": "https://test.move.mil/faqs",
      "params": {
        "headers": {
          "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "Upgrade-Insecure-Requests": "1",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css_1fyPiGw7SEkDSOqFRl6mSOQCVU-7EuP62Jvi_XUrQak.css?pb03ed",
      "params": {
        "headers": {
          "Accept": "text/css,*/*;q=0.1",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/faqs",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
      "params": {
        "headers": {
          "Referer": "https://test.move.mil/faqs",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/favicons/favicon-57.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-dot-gov.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-https.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/ustranscom-logo.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/close.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/js/js_AhbPR7vrUdq0UnLoU8TWkSvVlP4ScOUU8tYbORgwWcM.js"
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-italic-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/angle-arrow-down-primary.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/plus-blue.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/modules/contrib/extlink/extlink_s.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    }];
    res = http.batch(req);
    sleep(6.09);
  });
  group("page_6 - https://test.move.mil/customer-service", function() {
    let req, res;
    req = [{
      "method": "get",
      "url": "https://test.move.mil/customer-service",
      "params": {
        "headers": {
          "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "Upgrade-Insecure-Requests": "1",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
      "params": {
        "headers": {
          "Referer": "https://test.move.mil/customer-service",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
      "params": {
        "headers": {
          "Referer": "https://test.move.mil/customer-service",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/favicons/favicon-57.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-dot-gov.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-https.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/claims.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/customer-service",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/close.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/pppo.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/customer-service",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/techhelp.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/customer-service",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/servicebranchhelp.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/customer-service",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/ustranscom-logo.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/accounting.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/customer-service",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/core/misc/icons/e32700/error.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/customer-service",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/retirment-separation.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/customer-service",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/js/js_AhbPR7vrUdq0UnLoU8TWkSvVlP4ScOUU8tYbORgwWcM.js"
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-italic-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/angle-arrow-down-primary.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/modules/contrib/extlink/extlink_s.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    }];
    res = http.batch(req);
    sleep(6.08);
  });
  group("page_7 - https://test.move.mil/resources", function() {
    let req, res;
    req = [{
      "method": "get",
      "url": "https://test.move.mil/resources",
      "params": {
        "headers": {
          "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "Upgrade-Insecure-Requests": "1",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/ustranscom-logo.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed"
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed"
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/favicons/favicon-57.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-https.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/close.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/militaryonesource.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/resources",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/militaryinstallations.gif",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/resources",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/icon-dot-gov.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/js/js_AhbPR7vrUdq0UnLoU8TWkSvVlP4ScOUU8tYbORgwWcM.js"
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/iallogo.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/resources",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/sites/default/files/2018-04/sesamestreetlogo.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/resources",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-italic-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/sourcesanspro-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-regular-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/fonts/merriweather-bold-webfont.woff2",
      "params": {
        "headers": {
          "Origin": "https://test.move.mil",
          "Accept-Encoding": "gzip, deflate",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36",
          "Accept": "*/*",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Connection": "keep-alive"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/themes/custom/move_mil/assets/img/angle-arrow-down-primary.svg",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css__G25POVC8yPG_VwwKt9q3YDd0AtHZlnpTkyc5jul0RU.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    },{
      "method": "get",
      "url": "https://test.move.mil/modules/contrib/extlink/extlink_s.png",
      "params": {
        "headers": {
          "Accept": "image/webp,image/apng,image/*,*/*;q=0.8",
          "Connection": "keep-alive",
          "Accept-Encoding": "gzip, deflate",
          "Referer": "https://test.move.mil/sites/default/files/css/css_0TLWrW1lBIV3AklZBuud43UBgZlzcKOAKeSapFJz3vo.css?pb03ed",
          "Host": "test.move.mil",
          "Accept-Language": "en-US",
          "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/62.0.3183.0 Safari/537.36"
        }
      }
    }];
    res = http.batch(req);
    // Random sleep between 5s and 10s
    sleep(Math.floor(Math.random()*5+5));
  });

}