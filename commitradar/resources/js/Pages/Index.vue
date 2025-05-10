<script>
import CustomInput from "@/Components/CustomInput.vue";

export default {
    name: 'Index',
    components: {CustomInput },
    data() {
        return {
            showNotification: false,
            notificationMessage: "",
            dateFrom: this.getDefaultStartDate(),
            dateTo: this.getToday(),
            today: this.getToday()
        };
    },
    methods: {
        triggerNotification(message) {
            this.notificationMessage = message;
            this.showNotification = true;

            // Hide the notification after 5 seconds
            setTimeout(() => {
                this.showNotification = false;
                this.notificationMessage = '';
            }, 3000);
        },
        getToday() {
            const today = new Date();
            return today.toISOString().split('T')[0];
        },
        getDefaultStartDate() {
            const date = new Date();
            date.setDate(date.getDate() - 7);
            return date.toISOString().split('T')[0];
        },
    },
    props: {
        flash: {
            type: Object,
            default: () => ({}),
        },
    },
    mounted() {
        if (this.flash.auth_error) {
            setTimeout(() => {
                this.triggerNotification(this.flash.auth_error)
            }, 500);
        }
    },
};
</script>

<template>
    <div class="background-wrapper">
        <!-- Background Container -->
        <div class="background_container">
            <div id="stars"></div>
            <div id="stars2"></div>
            <div id="stars3"></div>
            <div class="circle circle1"></div>
            <div class="circle circle2"></div>
        </div>
        <!-- Notification Bar -->
        <transition name="slide-down">
            <div v-if="showNotification" class="notification-bar">
                {{ notificationMessage }}
            </div>
        </transition>
        <!-- Overlay Container  -->
        <div class="overlay">
            <p class="title-text">
                    CommitRad
                    <span class="radar-icon">
                        <svg fill="#9942E4" height="40px" width="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 511.032 511.032" xml:space="preserve">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                        <g id="SVGRepo_iconCarrier"> <path d="M471.016,23.5c0-12.958-10.542-23.5-23.5-23.5c-10.324,0-19.108,6.695-22.255,15.969h-246.67l-0.146-0.146 c-4.449-4.449-11.078-5.768-16.892-3.36c-5.813,2.408-9.568,8.029-9.568,14.32v257.58l-27.777,27.777 c-15.835,1.645-28.223,15.067-28.223,31.329c0,8.289,3.223,15.835,8.475,21.465c-0.159,0.445-0.282,0.91-0.356,1.395l-15.031,97.704 H63.516c-12.958,0-23.5,10.542-23.5,23.5v16c0,4.142,3.358,7.5,7.5,7.5h280c4.142,0,7.5-3.358,7.5-7.5v-16 c0-12.958-10.542-23.5-23.5-23.5h-52.83L154.427,359.774c2.355-3.876,3.896-8.298,4.387-13.028l27.777-27.777h257.58 c6.292,0,11.912-3.756,14.32-9.568s1.089-12.443-3.36-16.892l-0.146-0.146V45.776C464.291,42.647,471.016,33.847,471.016,23.5z M447.516,15c4.687,0,8.5,3.813,8.5,8.5c0,4.588-3.656,8.329-8.207,8.485c-0.108-0.005-0.215-0.016-0.325-0.016 c-0.099,0-0.195,0.011-0.293,0.015c-4.536-0.173-8.175-3.906-8.175-8.484C439.016,18.813,442.829,15,447.516,15z M322.121,159.498 L427.02,54.668L378.083,215.46L322.121,159.498z M311.514,148.891l-56.02-56.02l161.157-49.048L311.514,148.891z M407.37,30.969 L245.301,80.294c-0.592,0.18-1.14,0.434-1.649,0.736l-50.061-50.061H407.37z M167.293,26.321c0.309-0.128,0.418-0.019,0.545,0.108 l2.342,2.342c0.001,0.001,0.002,0.002,0.002,0.002l130.721,130.721L171.468,288.845l-4.483-4.483V26.783 C166.985,26.604,166.985,26.449,167.293,26.321z M118.167,373.561c2.946,0.914,6.075,1.407,9.318,1.407 c3.264,0,6.414-0.5,9.378-1.426l13.921,90.489h-46.536L118.167,373.561z M320.016,487.532v8.5h-265v-8.5c0-4.687,3.813-8.5,8.5-8.5 h248C316.203,479.032,320.016,482.845,320.016,487.532z M165.96,464.032l-13.002-84.515l84.515,84.515H165.96z M132.819,338.166 c-2.929-2.929-7.678-2.929-10.606,0c-2.929,2.929-2.929,7.678,0,10.606l10.378,10.378c-1.61,0.526-3.323,0.818-5.106,0.818 c-9.098,0-16.5-7.402-16.5-16.5s7.402-16.5,16.5-16.5c9.098,0,16.499,7.401,16.5,16.499c0,0,0,0.002,0,0.002 c0,1.765-0.286,3.464-0.802,5.06L132.819,338.166z M155.416,328.931c-2.988-5.717-7.677-10.406-13.394-13.394l17.463-17.462 l13.394,13.394L155.416,328.931z M444.633,303.66c-0.128,0.309-0.283,0.309-0.462,0.309h-257.58l-4.516-4.516l129.436-129.351 l130.67,130.67c0.001,0.001,0.002,0.002,0.002,0.002l2.342,2.342C444.651,303.242,444.761,303.352,444.633,303.66z M389.912,227.29 c0.302-0.509,0.568-1.046,0.748-1.637l49.325-162.068v213.778L389.912,227.29z"/> </g>
                        </svg>
                    </span>
                    r
                </p>
            <CustomInput :notificationHandler="triggerNotification"
                         :date-from="dateFrom"
                         :date-to="dateTo"
                         style="padding-bottom: 25px"/>

            <div style="display: flex; align-items: center; gap: 10px; padding-bottom: 25px; flex-wrap: wrap;">
                <label style="color: white;">Start date:</label>
                <input type="date" v-model="dateFrom" class="date-picker" :max="today"/>

                <span style="color: white;">–</span>

                <label style="color: white;">End date:</label>
                <input type="date" v-model="dateTo" class="date-picker" :max="today"/>
            </div>

        </div>
    </div>
</template>


<style scoped>

.notification-bar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: #6c5ce7;
    color: #fdfbd4;
    padding: 15px;
    text-align: center;
    font-size: 16px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

/* Animation for sliding down */
.slide-down-enter-active,
.slide-down-leave-active {
    transform: translateY(-100%);
    transition: transform 0.5s ease; /* Smooth animation */
}

.slide-down-enter-to {
    transform: translateY(0); /* End in view */
}

.slide-down-leave-active {
    transition: transform 0.5s ease; /* Smooth slide out */
}

.slide-down-leave-to {
    transform: translateY(-100%); /* Exit off-screen */
}

.settings-wrapper {
    display: flex;
    align-items: center;
    gap: 0;
    font-size: 23px;
    font-weight: bold;
    color: #fdfbd4;
    width: 200px;
}

.settings-text-container {
    position: relative;
    width: 200px; /* Fixed width to prevent shifting */
    height: 30px; /* Fixed height for both texts */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

/* Animation styles */
.fade-slide-enter-active, .fade-slide-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-slide-enter-from, .fade-slide-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

.card-fade-enter-active, .card-fade-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.card-fade-enter-from, .card-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.settings-label {
    position: absolute;
    width: 100%;
    text-align: right;
    cursor: pointer;
    user-select: none;
}

/* Placeholder with fixed height to avoid jumps */
.card-placeholder {
    position: relative;
    width: 100%;
    height: 260px; /* Match the height of SettingsCard to reserve space */
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

.settings-card {
    position: absolute;
    top: 0;
}

.background-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    overflow: hidden;
    z-index: -1;
}

/* Background stars and circles */
.background_container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

/* Overlay container for content above the background */
.overlay {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding-top: 120px;
}

/* Title text with SVG icon replacement */
.title-text {
    font-size: 3.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #f8a5c2, #6c5ce7);
    -webkit-background-clip: text;
    color: transparent;
    padding-bottom: 10px;
    display: flex;
    align-items: center;
    user-select: none;
}

.radar-icon {
    display: inline-block;
    width: 40px;
    height: 40px;
    margin: 0 5px;
    /* Adjust width/height for icon size */
}

/* Körök stílusa */
.circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
}

/* Kör méretek és pozíciók */
.circle1 {
    width: 300px;
    height: 300px;
    top: 20%;
    left: 10%;
}

.circle2 {
    width: 200px;
    height: 200px;
    bottom: 20%;
    right: 10%;
}
#stars {
    width: 1px;
    height: 1px;
    background: transparent;
    box-shadow: 1825px 612px #FFF , 610px 1784px #FFF , 576px 1497px #FFF , 1058px 1390px #FFF , 1251px 964px #FFF , 157px 764px #FFF , 882px 779px #FFF , 1241px 907px #FFF , 879px 411px #FFF , 1402px 489px #FFF , 479px 1975px #FFF , 141px 1130px #FFF , 1312px 1024px #FFF , 1468px 1262px #FFF , 1291px 541px #FFF , 1756px 316px #FFF , 993px 185px #FFF , 1634px 1830px #FFF , 537px 1097px #FFF , 545px 756px #FFF , 9px 209px #FFF , 597px 977px #FFF , 85px 906px #FFF , 1789px 482px #FFF , 803px 1212px #FFF , 341px 1109px #FFF , 1549px 1517px #FFF , 612px 888px #FFF , 924px 74px #FFF , 1063px 120px #FFF , 1941px 286px #FFF , 661px 1612px #FFF , 1139px 1801px #FFF , 554px 361px #FFF , 1910px 269px #FFF , 694px 1213px #FFF , 1702px 1093px #FFF , 1990px 703px #FFF , 1659px 1170px #FFF , 848px 1450px #FFF , 1251px 1162px #FFF , 462px 1010px #FFF , 1456px 596px #FFF , 1657px 312px #FFF , 823px 1543px #FFF , 998px 806px #FFF , 1875px 1040px #FFF , 1769px 1961px #FFF , 799px 67px #FFF , 1087px 312px #FFF , 1593px 1241px #FFF , 1369px 995px #FFF , 1616px 1523px #FFF , 448px 246px #FFF , 206px 78px #FFF , 1550px 574px #FFF , 1078px 126px #FFF , 1297px 809px #FFF , 572px 1904px #FFF , 118px 960px #FFF , 575px 717px #FFF , 117px 50px #FFF , 376px 814px #FFF , 62px 1329px #FFF , 551px 143px #FFF , 1013px 248px #FFF , 751px 729px #FFF , 1609px 345px #FFF , 540px 1342px #FFF , 1827px 1160px #FFF , 712px 1339px #FFF , 1578px 498px #FFF , 1252px 741px #FFF , 21px 1525px #FFF , 1039px 1666px #FFF , 1629px 51px #FFF , 36px 1547px #FFF , 1630px 146px #FFF , 375px 1659px #FFF , 723px 465px #FFF , 935px 1727px #FFF , 388px 449px #FFF , 1253px 1346px #FFF , 261px 1066px #FFF , 693px 1544px #FFF , 1884px 1067px #FFF , 96px 961px #FFF , 1201px 1474px #FFF , 151px 695px #FFF , 1567px 664px #FFF , 1947px 411px #FFF , 1694px 1710px #FFF , 841px 1013px #FFF , 821px 709px #FFF , 475px 1237px #FFF , 1911px 1460px #FFF , 984px 629px #FFF , 942px 1638px #FFF , 5px 1642px #FFF , 504px 887px #FFF , 202px 1795px #FFF , 1005px 77px #FFF , 1732px 1901px #FFF , 494px 1818px #FFF , 755px 432px #FFF , 802px 379px #FFF , 265px 315px #FFF , 912px 1819px #FFF , 954px 342px #FFF , 961px 436px #FFF , 1626px 1336px #FFF , 1980px 606px #FFF , 504px 1224px #FFF , 251px 393px #FFF , 71px 383px #FFF , 1366px 1963px #FFF , 1810px 1055px #FFF , 186px 456px #FFF , 685px 591px #FFF , 576px 286px #FFF , 762px 1960px #FFF , 1770px 1650px #FFF , 1315px 116px #FFF , 681px 1495px #FFF , 1661px 1789px #FFF , 2px 780px #FFF , 1283px 378px #FFF , 1693px 1752px #FFF , 1321px 1003px #FFF , 309px 744px #FFF , 1808px 400px #FFF , 1864px 1592px #FFF , 1582px 171px #FFF , 978px 297px #FFF , 1625px 303px #FFF , 1409px 1477px #FFF , 1293px 8px #FFF , 330px 430px #FFF , 441px 888px #FFF , 971px 1868px #FFF , 1121px 1053px #FFF , 872px 689px #FFF , 1317px 1560px #FFF , 445px 1929px #FFF , 318px 509px #FFF , 1585px 337px #FFF , 1093px 1358px #FFF , 874px 438px #FFF , 1732px 1556px #FFF , 1618px 1177px #FFF , 1960px 1592px #FFF , 233px 1793px #FFF , 142px 1466px #FFF , 1782px 1365px #FFF , 1654px 134px #FFF , 1741px 1620px #FFF , 171px 781px #FFF , 1536px 1624px #FFF , 219px 439px #FFF , 1231px 569px #FFF , 1227px 1559px #FFF , 241px 1648px #FFF , 1896px 1968px #FFF , 696px 927px #FFF , 98px 1840px #FFF , 418px 1866px #FFF , 1534px 437px #FFF , 145px 367px #FFF , 124px 1773px #FFF , 230px 1540px #FFF , 741px 97px #FFF , 413px 206px #FFF , 1507px 404px #FFF , 1170px 1438px #FFF , 794px 227px #FFF , 1664px 428px #FFF , 1883px 208px #FFF , 83px 1315px #FFF , 1473px 1641px #FFF , 1745px 1028px #FFF , 886px 906px #FFF , 824px 178px #FFF , 1450px 1955px #FFF , 964px 138px #FFF , 1809px 522px #FFF , 365px 1548px #FFF , 108px 1890px #FFF , 296px 702px #FFF , 313px 1704px #FFF , 1134px 510px #FFF , 300px 256px #FFF , 295px 177px #FFF , 1712px 1100px #FFF , 566px 230px #FFF , 731px 843px #FFF , 1019px 1654px #FFF , 1090px 789px #FFF , 146px 1066px #FFF , 102px 647px #FFF , 1496px 1972px #FFF , 1950px 45px #FFF , 1346px 1303px #FFF , 64px 885px #FFF , 121px 1212px #FFF , 642px 1664px #FFF , 1364px 1324px #FFF , 266px 1548px #FFF , 1782px 1936px #FFF , 1825px 1711px #FFF , 418px 127px #FFF , 768px 1473px #FFF , 521px 939px #FFF , 1088px 7px #FFF , 659px 733px #FFF , 1729px 282px #FFF , 433px 422px #FFF , 696px 281px #FFF , 1219px 776px #FFF , 1678px 691px #FFF , 806px 1069px #FFF , 1000px 1317px #FFF , 1536px 973px #FFF , 1924px 341px #FFF , 177px 1605px #FFF , 288px 1160px #FFF , 1220px 1393px #FFF , 60px 594px #FFF , 865px 1924px #FFF , 798px 38px #FFF , 534px 970px #FFF , 721px 355px #FFF , 41px 1159px #FFF , 1184px 1525px #FFF , 1058px 608px #FFF , 1250px 1661px #FFF , 398px 727px #FFF , 159px 492px #FFF , 361px 199px #FFF , 1751px 406px #FFF , 1859px 559px #FFF , 689px 612px #FFF , 1899px 1094px #FFF , 165px 378px #FFF , 985px 346px #FFF , 1376px 891px #FFF , 1843px 1408px #FFF , 225px 707px #FFF , 906px 745px #FFF , 655px 368px #FFF , 1037px 649px #FFF , 1772px 906px #FFF , 1400px 733px #FFF , 1938px 1760px #FFF , 1888px 838px #FFF , 1903px 564px #FFF , 1336px 471px #FFF , 857px 1953px #FFF , 1691px 492px #FFF , 127px 1113px #FFF , 750px 27px #FFF , 1519px 1580px #FFF , 1289px 1066px #FFF , 1277px 1556px #FFF , 574px 1873px #FFF , 24px 1830px #FFF , 779px 1906px #FFF , 1587px 1717px #FFF , 1081px 448px #FFF , 1997px 548px #FFF , 1144px 69px #FFF , 1096px 298px #FFF , 1776px 257px #FFF , 1073px 1784px #FFF , 791px 837px #FFF , 1087px 1791px #FFF , 225px 807px #FFF , 40px 462px #FFF , 1391px 1215px #FFF , 1940px 1867px #FFF , 1968px 1932px #FFF , 752px 359px #FFF , 21px 1628px #FFF , 1731px 957px #FFF , 1633px 1742px #FFF , 591px 607px #FFF , 1480px 224px #FFF , 1742px 930px #FFF , 1953px 277px #FFF , 325px 1466px #FFF , 9px 1905px #FFF , 817px 517px #FFF , 1530px 1792px #FFF , 1672px 366px #FFF , 807px 187px #FFF , 1401px 1638px #FFF , 554px 1857px #FFF , 1240px 921px #FFF , 619px 494px #FFF , 921px 1920px #FFF , 7px 1551px #FFF , 1546px 505px #FFF , 1714px 1348px #FFF , 242px 475px #FFF , 1271px 1199px #FFF , 709px 684px #FFF , 771px 1267px #FFF , 135px 559px #FFF , 550px 1145px #FFF , 1733px 1371px #FFF , 412px 168px #FFF , 1226px 1483px #FFF , 19px 59px #FFF , 784px 368px #FFF , 157px 1123px #FFF , 472px 16px #FFF , 127px 867px #FFF , 133px 1266px #FFF , 565px 1498px #FFF , 1788px 1060px #FFF , 1571px 1181px #FFF , 47px 1865px #FFF , 1219px 1402px #FFF , 263px 1044px #FFF , 993px 1204px #FFF , 1345px 1169px #FFF , 1586px 1754px #FFF , 1775px 1765px #FFF , 1861px 1614px #FFF , 901px 1148px #FFF , 1680px 1749px #FFF , 123px 445px #FFF , 1264px 31px #FFF , 698px 1772px #FFF , 1084px 1596px #FFF , 1915px 397px #FFF , 676px 1462px #FFF , 637px 767px #FFF , 1956px 142px #FFF , 499px 1599px #FFF , 1852px 1219px #FFF , 1843px 621px #FFF , 1561px 960px #FFF , 1845px 725px #FFF , 979px 1865px #FFF , 368px 1191px #FFF , 762px 1800px #FFF , 1178px 1801px #FFF , 1173px 721px #FFF , 865px 752px #FFF , 1811px 1132px #FFF , 1807px 415px #FFF , 1225px 688px #FFF , 1257px 110px #FFF , 681px 514px #FFF , 321px 1217px #FFF , 941px 1122px #FFF , 293px 205px #FFF , 854px 1513px #FFF , 1905px 1901px #FFF , 1649px 1375px #FFF , 1897px 1149px #FFF , 1704px 1865px #FFF , 261px 728px #FFF , 1939px 522px #FFF , 1931px 1396px #FFF , 805px 1808px #FFF , 365px 1403px #FFF , 817px 1705px #FFF , 1759px 1080px #FFF , 1488px 551px #FFF , 75px 206px #FFF , 1594px 610px #FFF , 623px 1797px #FFF , 686px 741px #FFF , 476px 261px #FFF , 769px 1307px #FFF , 425px 101px #FFF , 863px 723px #FFF , 1715px 261px #FFF , 1685px 1137px #FFF , 1594px 966px #FFF , 1779px 1959px #FFF , 624px 996px #FFF , 1400px 534px #FFF , 1761px 903px #FFF , 1316px 1439px #FFF , 1739px 1121px #FFF , 1160px 1503px #FFF , 871px 217px #FFF , 141px 1707px #FFF , 1472px 1925px #FFF , 730px 1765px #FFF , 323px 1045px #FFF , 214px 1614px #FFF , 1726px 1440px #FFF , 1746px 515px #FFF , 312px 161px #FFF , 949px 1085px #FFF , 247px 1550px #FFF , 534px 1477px #FFF , 1642px 879px #FFF , 802px 1769px #FFF , 673px 619px #FFF , 548px 795px #FFF , 764px 1969px #FFF , 1772px 951px #FFF , 1983px 656px #FFF , 888px 451px #FFF , 418px 156px #FFF , 809px 1815px #FFF , 177px 1827px #FFF , 1114px 619px #FFF , 430px 419px #FFF , 1217px 1827px #FFF , 358px 766px #FFF , 1731px 124px #FFF , 1998px 1371px #FFF , 973px 798px #FFF , 185px 723px #FFF , 921px 998px #FFF , 1596px 114px #FFF , 284px 28px #FFF , 251px 642px #FFF , 1524px 1379px #FFF , 1949px 257px #FFF , 1546px 1414px #FFF , 1470px 1151px #FFF , 120px 1368px #FFF , 1072px 348px #FFF , 40px 464px #FFF , 622px 1476px #FFF , 360px 134px #FFF , 1983px 952px #FFF , 383px 479px #FFF , 1169px 185px #FFF , 455px 565px #FFF , 767px 820px #FFF , 1082px 1181px #FFF , 373px 248px #FFF , 533px 785px #FFF , 33px 1261px #FFF , 349px 1588px #FFF , 157px 1704px #FFF , 635px 1526px #FFF , 657px 1799px #FFF , 1192px 1302px #FFF , 814px 529px #FFF , 1003px 1434px #FFF , 910px 239px #FFF , 259px 610px #FFF , 891px 1864px #FFF , 905px 1815px #FFF , 91px 1973px #FFF , 1138px 232px #FFF , 597px 1113px #FFF , 1939px 1637px #FFF , 1962px 1688px #FFF , 1913px 1706px #FFF , 1797px 1778px #FFF , 342px 1418px #FFF , 101px 1082px #FFF , 1046px 385px #FFF , 398px 1872px #FFF , 623px 1370px #FFF , 324px 1695px #FFF , 811px 1942px #FFF , 2000px 1133px #FFF , 1857px 1427px #FFF , 1092px 178px #FFF , 1933px 1537px #FFF , 1532px 1751px #FFF , 911px 428px #FFF , 1521px 146px #FFF , 1670px 1733px #FFF , 1663px 1879px #FFF , 1361px 261px #FFF , 1029px 529px #FFF , 869px 845px #FFF , 1267px 1250px #FFF , 1815px 578px #FFF , 1758px 1848px #FFF , 1582px 1485px #FFF , 1143px 1402px #FFF , 316px 719px #FFF , 1875px 1081px #FFF , 878px 1114px #FFF , 1873px 279px #FFF , 882px 1578px #FFF , 1971px 503px #FFF , 1218px 1435px #FFF , 1679px 1634px #FFF , 1353px 1835px #FFF , 1870px 934px #FFF , 394px 1589px #FFF , 99px 885px #FFF , 992px 326px #FFF , 19px 1585px #FFF , 42px 1321px #FFF , 964px 1933px #FFF , 1272px 764px #FFF , 1442px 1516px #FFF , 732px 958px #FFF , 463px 665px #FFF , 852px 1263px #FFF , 960px 902px #FFF , 1133px 668px #FFF , 578px 1208px #FFF , 1717px 1182px #FFF , 1249px 1170px #FFF , 1369px 1425px #FFF , 522px 1730px #FFF , 1141px 1158px #FFF , 1814px 1224px #FFF , 294px 79px #FFF , 1785px 1178px #FFF , 1519px 1570px #FFF , 475px 1779px #FFF , 159px 574px #FFF , 1952px 1211px #FFF , 1969px 1882px #FFF , 238px 925px #FFF , 364px 408px #FFF , 1602px 1803px #FFF , 1338px 1955px #FFF , 1114px 731px #FFF , 444px 734px #FFF , 904px 1776px #FFF , 1984px 375px #FFF , 490px 1272px #FFF , 234px 699px #FFF , 201px 1453px #FFF , 1675px 1941px #FFF , 1195px 621px #FFF , 700px 1362px #FFF , 475px 647px #FFF , 1312px 23px #FFF , 1375px 897px #FFF , 1148px 198px #FFF , 1048px 1419px #FFF , 270px 1893px #FFF , 1177px 51px #FFF , 25px 24px #FFF , 1061px 189px #FFF , 925px 1338px #FFF , 1970px 195px #FFF , 1376px 1488px #FFF , 1000px 1291px #FFF , 1442px 1680px #FFF , 1436px 238px #FFF , 237px 315px #FFF , 780px 1437px #FFF , 1049px 443px #FFF , 832px 1031px #FFF , 1267px 88px #FFF , 119px 331px #FFF , 1935px 1488px #FFF , 380px 522px #FFF , 503px 570px #FFF , 388px 1852px #FFF , 1003px 1801px #FFF , 410px 36px #FFF , 2000px 1470px #FFF , 72px 293px #FFF , 801px 1087px #FFF , 1523px 1803px #FFF , 509px 1738px #FFF , 1544px 1842px #FFF , 1227px 1968px #FFF , 984px 148px #FFF , 135px 1402px #FFF , 517px 1336px #FFF , 517px 834px #FFF , 1707px 1169px #FFF , 1076px 1628px #FFF , 1092px 1452px #FFF , 1106px 656px #FFF , 164px 1880px #FFF , 1185px 1801px #FFF , 1919px 222px #FFF , 462px 1622px #FFF , 1287px 1035px #FFF , 527px 1898px #FFF , 1864px 64px #FFF , 559px 363px #FFF , 1761px 1978px #FFF , 681px 626px #FFF , 228px 28px #FFF , 1550px 1191px #FFF , 817px 1019px #FFF , 555px 1640px #FFF , 1234px 254px #FFF , 1233px 227px #FFF , 568px 989px #FFF , 1091px 516px #FFF , 1425px 423px #FFF , 82px 1928px #FFF , 1823px 122px #FFF , 1900px 1947px #FFF , 708px 609px #FFF , 749px 156px #FFF , 195px 492px #FFF , 1899px 947px #FFF , 1166px 1677px #FFF , 1257px 256px #FFF , 1504px 48px #FFF , 599px 686px #FFF , 183px 33px #FFF , 1422px 1149px #FFF , 1058px 453px #FFF , 1077px 108px #FFF , 401px 1574px #FFF , 1490px 29px #FFF , 794px 1610px #FFF , 694px 1563px #FFF , 319px 1629px #FFF , 1772px 1327px #FFF , 276px 358px #FFF , 1491px 1942px #FFF , 1352px 993px #FFF , 722px 82px #FFF , 1530px 1202px #FFF , 1461px 215px #FFF , 338px 765px #FFF , 1266px 865px #FFF , 620px 797px #FFF , 642px 507px #FFF , 403px 1194px #FFF , 592px 97px #FFF , 852px 1094px #FFF , 959px 1927px #FFF , 1040px 386px #FFF , 85px 1729px #FFF , 293px 1108px #FFF , 1623px 1628px #FFF , 1699px 866px #FFF , 719px 1086px #FFF , 1944px 723px #FFF , 780px 39px #FFF , 1626px 634px #FFF , 772px 820px #FFF , 156px 1398px #FFF , 1145px 869px #FFF , 1908px 1441px #FFF , 804px 1855px #FFF , 179px 1862px #FFF , 1095px 1387px #FFF , 1089px 1372px #FFF , 1205px 1653px #FFF , 449px 1816px #FFF , 352px 1721px #FFF , 1846px 180px #FFF , 496px 369px #FFF , 1731px 498px #FFF , 1935px 1836px #FFF , 1567px 909px #FFF , 1486px 1777px #FFF , 539px 1127px #FFF , 891px 1877px #FFF , 64px 687px #FFF , 958px 1961px #FFF , 1403px 757px #FFF , 1206px 199px #FFF , 214px 20px #FFF , 1054px 1910px #FFF , 2px 1611px #FFF , 22px 511px #FFF , 454px 1409px #FFF , 517px 808px #FFF , 1120px 1620px #FFF , 1584px 1570px #FFF , 410px 155px #FFF , 1207px 1397px #FFF , 1589px 1757px #FFF , 24px 132px #FFF , 1891px 83px #FFF , 1872px 758px #FFF , 101px 955px #FFF , 963px 554px #FFF , 588px 856px #FFF , 1098px 1174px #FFF , 292px 981px #FFF , 969px 1191px #FFF , 1163px 729px #FFF , 492px 1190px #FFF , 982px 324px #FFF , 129px 1957px #FFF , 1515px 1011px #FFF , 1234px 109px #FFF , 1601px 828px #FFF , 1221px 1174px #FFF , 1258px 1669px #FFF , 33px 1026px #FFF , 639px 559px #FFF , 1328px 915px #FFF , 1093px 1796px #FFF , 816px 50px #FFF , 823px 1563px #FFF , 702px 37px #FFF , 1948px 1232px #FFF , 1431px 564px #FFF;
    animation: animStar 50s linear infinite;
}
#stars:after {
    content: " ";
    position: absolute;
    top: 2000px;
    width: 1px;
    height: 1px;
    background: transparent;
    box-shadow: 1825px 612px #FFF , 610px 1784px #FFF , 576px 1497px #FFF , 1058px 1390px #FFF , 1251px 964px #FFF , 157px 764px #FFF , 882px 779px #FFF , 1241px 907px #FFF , 879px 411px #FFF , 1402px 489px #FFF , 479px 1975px #FFF , 141px 1130px #FFF , 1312px 1024px #FFF , 1468px 1262px #FFF , 1291px 541px #FFF , 1756px 316px #FFF , 993px 185px #FFF , 1634px 1830px #FFF , 537px 1097px #FFF , 545px 756px #FFF , 9px 209px #FFF , 597px 977px #FFF , 85px 906px #FFF , 1789px 482px #FFF , 803px 1212px #FFF , 341px 1109px #FFF , 1549px 1517px #FFF , 612px 888px #FFF , 924px 74px #FFF , 1063px 120px #FFF , 1941px 286px #FFF , 661px 1612px #FFF , 1139px 1801px #FFF , 554px 361px #FFF , 1910px 269px #FFF , 694px 1213px #FFF , 1702px 1093px #FFF , 1990px 703px #FFF , 1659px 1170px #FFF , 848px 1450px #FFF , 1251px 1162px #FFF , 462px 1010px #FFF , 1456px 596px #FFF , 1657px 312px #FFF , 823px 1543px #FFF , 998px 806px #FFF , 1875px 1040px #FFF , 1769px 1961px #FFF , 799px 67px #FFF , 1087px 312px #FFF , 1593px 1241px #FFF , 1369px 995px #FFF , 1616px 1523px #FFF , 448px 246px #FFF , 206px 78px #FFF , 1550px 574px #FFF , 1078px 126px #FFF , 1297px 809px #FFF , 572px 1904px #FFF , 118px 960px #FFF , 575px 717px #FFF , 117px 50px #FFF , 376px 814px #FFF , 62px 1329px #FFF , 551px 143px #FFF , 1013px 248px #FFF , 751px 729px #FFF , 1609px 345px #FFF , 540px 1342px #FFF , 1827px 1160px #FFF , 712px 1339px #FFF , 1578px 498px #FFF , 1252px 741px #FFF , 21px 1525px #FFF , 1039px 1666px #FFF , 1629px 51px #FFF , 36px 1547px #FFF , 1630px 146px #FFF , 375px 1659px #FFF , 723px 465px #FFF , 935px 1727px #FFF , 388px 449px #FFF , 1253px 1346px #FFF , 261px 1066px #FFF , 693px 1544px #FFF , 1884px 1067px #FFF , 96px 961px #FFF , 1201px 1474px #FFF , 151px 695px #FFF , 1567px 664px #FFF , 1947px 411px #FFF , 1694px 1710px #FFF , 841px 1013px #FFF , 821px 709px #FFF , 475px 1237px #FFF , 1911px 1460px #FFF , 984px 629px #FFF , 942px 1638px #FFF , 5px 1642px #FFF , 504px 887px #FFF , 202px 1795px #FFF , 1005px 77px #FFF , 1732px 1901px #FFF , 494px 1818px #FFF , 755px 432px #FFF , 802px 379px #FFF , 265px 315px #FFF , 912px 1819px #FFF , 954px 342px #FFF , 961px 436px #FFF , 1626px 1336px #FFF , 1980px 606px #FFF , 504px 1224px #FFF , 251px 393px #FFF , 71px 383px #FFF , 1366px 1963px #FFF , 1810px 1055px #FFF , 186px 456px #FFF , 685px 591px #FFF , 576px 286px #FFF , 762px 1960px #FFF , 1770px 1650px #FFF , 1315px 116px #FFF , 681px 1495px #FFF , 1661px 1789px #FFF , 2px 780px #FFF , 1283px 378px #FFF , 1693px 1752px #FFF , 1321px 1003px #FFF , 309px 744px #FFF , 1808px 400px #FFF , 1864px 1592px #FFF , 1582px 171px #FFF , 978px 297px #FFF , 1625px 303px #FFF , 1409px 1477px #FFF , 1293px 8px #FFF , 330px 430px #FFF , 441px 888px #FFF , 971px 1868px #FFF , 1121px 1053px #FFF , 872px 689px #FFF , 1317px 1560px #FFF , 445px 1929px #FFF , 318px 509px #FFF , 1585px 337px #FFF , 1093px 1358px #FFF , 874px 438px #FFF , 1732px 1556px #FFF , 1618px 1177px #FFF , 1960px 1592px #FFF , 233px 1793px #FFF , 142px 1466px #FFF , 1782px 1365px #FFF , 1654px 134px #FFF , 1741px 1620px #FFF , 171px 781px #FFF , 1536px 1624px #FFF , 219px 439px #FFF , 1231px 569px #FFF , 1227px 1559px #FFF , 241px 1648px #FFF , 1896px 1968px #FFF , 696px 927px #FFF , 98px 1840px #FFF , 418px 1866px #FFF , 1534px 437px #FFF , 145px 367px #FFF , 124px 1773px #FFF , 230px 1540px #FFF , 741px 97px #FFF , 413px 206px #FFF , 1507px 404px #FFF , 1170px 1438px #FFF , 794px 227px #FFF , 1664px 428px #FFF , 1883px 208px #FFF , 83px 1315px #FFF , 1473px 1641px #FFF , 1745px 1028px #FFF , 886px 906px #FFF , 824px 178px #FFF , 1450px 1955px #FFF , 964px 138px #FFF , 1809px 522px #FFF , 365px 1548px #FFF , 108px 1890px #FFF , 296px 702px #FFF , 313px 1704px #FFF , 1134px 510px #FFF , 300px 256px #FFF , 295px 177px #FFF , 1712px 1100px #FFF , 566px 230px #FFF , 731px 843px #FFF , 1019px 1654px #FFF , 1090px 789px #FFF , 146px 1066px #FFF , 102px 647px #FFF , 1496px 1972px #FFF , 1950px 45px #FFF , 1346px 1303px #FFF , 64px 885px #FFF , 121px 1212px #FFF , 642px 1664px #FFF , 1364px 1324px #FFF , 266px 1548px #FFF , 1782px 1936px #FFF , 1825px 1711px #FFF , 418px 127px #FFF , 768px 1473px #FFF , 521px 939px #FFF , 1088px 7px #FFF , 659px 733px #FFF , 1729px 282px #FFF , 433px 422px #FFF , 696px 281px #FFF , 1219px 776px #FFF , 1678px 691px #FFF , 806px 1069px #FFF , 1000px 1317px #FFF , 1536px 973px #FFF , 1924px 341px #FFF , 177px 1605px #FFF , 288px 1160px #FFF , 1220px 1393px #FFF , 60px 594px #FFF , 865px 1924px #FFF , 798px 38px #FFF , 534px 970px #FFF , 721px 355px #FFF , 41px 1159px #FFF , 1184px 1525px #FFF , 1058px 608px #FFF , 1250px 1661px #FFF , 398px 727px #FFF , 159px 492px #FFF , 361px 199px #FFF , 1751px 406px #FFF , 1859px 559px #FFF , 689px 612px #FFF , 1899px 1094px #FFF , 165px 378px #FFF , 985px 346px #FFF , 1376px 891px #FFF , 1843px 1408px #FFF , 225px 707px #FFF , 906px 745px #FFF , 655px 368px #FFF , 1037px 649px #FFF , 1772px 906px #FFF , 1400px 733px #FFF , 1938px 1760px #FFF , 1888px 838px #FFF , 1903px 564px #FFF , 1336px 471px #FFF , 857px 1953px #FFF , 1691px 492px #FFF , 127px 1113px #FFF , 750px 27px #FFF , 1519px 1580px #FFF , 1289px 1066px #FFF , 1277px 1556px #FFF , 574px 1873px #FFF , 24px 1830px #FFF , 779px 1906px #FFF , 1587px 1717px #FFF , 1081px 448px #FFF , 1997px 548px #FFF , 1144px 69px #FFF , 1096px 298px #FFF , 1776px 257px #FFF , 1073px 1784px #FFF , 791px 837px #FFF , 1087px 1791px #FFF , 225px 807px #FFF , 40px 462px #FFF , 1391px 1215px #FFF , 1940px 1867px #FFF , 1968px 1932px #FFF , 752px 359px #FFF , 21px 1628px #FFF , 1731px 957px #FFF , 1633px 1742px #FFF , 591px 607px #FFF , 1480px 224px #FFF , 1742px 930px #FFF , 1953px 277px #FFF , 325px 1466px #FFF , 9px 1905px #FFF , 817px 517px #FFF , 1530px 1792px #FFF , 1672px 366px #FFF , 807px 187px #FFF , 1401px 1638px #FFF , 554px 1857px #FFF , 1240px 921px #FFF , 619px 494px #FFF , 921px 1920px #FFF , 7px 1551px #FFF , 1546px 505px #FFF , 1714px 1348px #FFF , 242px 475px #FFF , 1271px 1199px #FFF , 709px 684px #FFF , 771px 1267px #FFF , 135px 559px #FFF , 550px 1145px #FFF , 1733px 1371px #FFF , 412px 168px #FFF , 1226px 1483px #FFF , 19px 59px #FFF , 784px 368px #FFF , 157px 1123px #FFF , 472px 16px #FFF , 127px 867px #FFF , 133px 1266px #FFF , 565px 1498px #FFF , 1788px 1060px #FFF , 1571px 1181px #FFF , 47px 1865px #FFF , 1219px 1402px #FFF , 263px 1044px #FFF , 993px 1204px #FFF , 1345px 1169px #FFF , 1586px 1754px #FFF , 1775px 1765px #FFF , 1861px 1614px #FFF , 901px 1148px #FFF , 1680px 1749px #FFF , 123px 445px #FFF , 1264px 31px #FFF , 698px 1772px #FFF , 1084px 1596px #FFF , 1915px 397px #FFF , 676px 1462px #FFF , 637px 767px #FFF , 1956px 142px #FFF , 499px 1599px #FFF , 1852px 1219px #FFF , 1843px 621px #FFF , 1561px 960px #FFF , 1845px 725px #FFF , 979px 1865px #FFF , 368px 1191px #FFF , 762px 1800px #FFF , 1178px 1801px #FFF , 1173px 721px #FFF , 865px 752px #FFF , 1811px 1132px #FFF , 1807px 415px #FFF , 1225px 688px #FFF , 1257px 110px #FFF , 681px 514px #FFF , 321px 1217px #FFF , 941px 1122px #FFF , 293px 205px #FFF , 854px 1513px #FFF , 1905px 1901px #FFF , 1649px 1375px #FFF , 1897px 1149px #FFF , 1704px 1865px #FFF , 261px 728px #FFF , 1939px 522px #FFF , 1931px 1396px #FFF , 805px 1808px #FFF , 365px 1403px #FFF , 817px 1705px #FFF , 1759px 1080px #FFF , 1488px 551px #FFF , 75px 206px #FFF , 1594px 610px #FFF , 623px 1797px #FFF , 686px 741px #FFF , 476px 261px #FFF , 769px 1307px #FFF , 425px 101px #FFF , 863px 723px #FFF , 1715px 261px #FFF , 1685px 1137px #FFF , 1594px 966px #FFF , 1779px 1959px #FFF , 624px 996px #FFF , 1400px 534px #FFF , 1761px 903px #FFF , 1316px 1439px #FFF , 1739px 1121px #FFF , 1160px 1503px #FFF , 871px 217px #FFF , 141px 1707px #FFF , 1472px 1925px #FFF , 730px 1765px #FFF , 323px 1045px #FFF , 214px 1614px #FFF , 1726px 1440px #FFF , 1746px 515px #FFF , 312px 161px #FFF , 949px 1085px #FFF , 247px 1550px #FFF , 534px 1477px #FFF , 1642px 879px #FFF , 802px 1769px #FFF , 673px 619px #FFF , 548px 795px #FFF , 764px 1969px #FFF , 1772px 951px #FFF , 1983px 656px #FFF , 888px 451px #FFF , 418px 156px #FFF , 809px 1815px #FFF , 177px 1827px #FFF , 1114px 619px #FFF , 430px 419px #FFF , 1217px 1827px #FFF , 358px 766px #FFF , 1731px 124px #FFF , 1998px 1371px #FFF , 973px 798px #FFF , 185px 723px #FFF , 921px 998px #FFF , 1596px 114px #FFF , 284px 28px #FFF , 251px 642px #FFF , 1524px 1379px #FFF , 1949px 257px #FFF , 1546px 1414px #FFF , 1470px 1151px #FFF , 120px 1368px #FFF , 1072px 348px #FFF , 40px 464px #FFF , 622px 1476px #FFF , 360px 134px #FFF , 1983px 952px #FFF , 383px 479px #FFF , 1169px 185px #FFF , 455px 565px #FFF , 767px 820px #FFF , 1082px 1181px #FFF , 373px 248px #FFF , 533px 785px #FFF , 33px 1261px #FFF , 349px 1588px #FFF , 157px 1704px #FFF , 635px 1526px #FFF , 657px 1799px #FFF , 1192px 1302px #FFF , 814px 529px #FFF , 1003px 1434px #FFF , 910px 239px #FFF , 259px 610px #FFF , 891px 1864px #FFF , 905px 1815px #FFF , 91px 1973px #FFF , 1138px 232px #FFF , 597px 1113px #FFF , 1939px 1637px #FFF , 1962px 1688px #FFF , 1913px 1706px #FFF , 1797px 1778px #FFF , 342px 1418px #FFF , 101px 1082px #FFF , 1046px 385px #FFF , 398px 1872px #FFF , 623px 1370px #FFF , 324px 1695px #FFF , 811px 1942px #FFF , 2000px 1133px #FFF , 1857px 1427px #FFF , 1092px 178px #FFF , 1933px 1537px #FFF , 1532px 1751px #FFF , 911px 428px #FFF , 1521px 146px #FFF , 1670px 1733px #FFF , 1663px 1879px #FFF , 1361px 261px #FFF , 1029px 529px #FFF , 869px 845px #FFF , 1267px 1250px #FFF , 1815px 578px #FFF , 1758px 1848px #FFF , 1582px 1485px #FFF , 1143px 1402px #FFF , 316px 719px #FFF , 1875px 1081px #FFF , 878px 1114px #FFF , 1873px 279px #FFF , 882px 1578px #FFF , 1971px 503px #FFF , 1218px 1435px #FFF , 1679px 1634px #FFF , 1353px 1835px #FFF , 1870px 934px #FFF , 394px 1589px #FFF , 99px 885px #FFF , 992px 326px #FFF , 19px 1585px #FFF , 42px 1321px #FFF , 964px 1933px #FFF , 1272px 764px #FFF , 1442px 1516px #FFF , 732px 958px #FFF , 463px 665px #FFF , 852px 1263px #FFF , 960px 902px #FFF , 1133px 668px #FFF , 578px 1208px #FFF , 1717px 1182px #FFF , 1249px 1170px #FFF , 1369px 1425px #FFF , 522px 1730px #FFF , 1141px 1158px #FFF , 1814px 1224px #FFF , 294px 79px #FFF , 1785px 1178px #FFF , 1519px 1570px #FFF , 475px 1779px #FFF , 159px 574px #FFF , 1952px 1211px #FFF , 1969px 1882px #FFF , 238px 925px #FFF , 364px 408px #FFF , 1602px 1803px #FFF , 1338px 1955px #FFF , 1114px 731px #FFF , 444px 734px #FFF , 904px 1776px #FFF , 1984px 375px #FFF , 490px 1272px #FFF , 234px 699px #FFF , 201px 1453px #FFF , 1675px 1941px #FFF , 1195px 621px #FFF , 700px 1362px #FFF , 475px 647px #FFF , 1312px 23px #FFF , 1375px 897px #FFF , 1148px 198px #FFF , 1048px 1419px #FFF , 270px 1893px #FFF , 1177px 51px #FFF , 25px 24px #FFF , 1061px 189px #FFF , 925px 1338px #FFF , 1970px 195px #FFF , 1376px 1488px #FFF , 1000px 1291px #FFF , 1442px 1680px #FFF , 1436px 238px #FFF , 237px 315px #FFF , 780px 1437px #FFF , 1049px 443px #FFF , 832px 1031px #FFF , 1267px 88px #FFF , 119px 331px #FFF , 1935px 1488px #FFF , 380px 522px #FFF , 503px 570px #FFF , 388px 1852px #FFF , 1003px 1801px #FFF , 410px 36px #FFF , 2000px 1470px #FFF , 72px 293px #FFF , 801px 1087px #FFF , 1523px 1803px #FFF , 509px 1738px #FFF , 1544px 1842px #FFF , 1227px 1968px #FFF , 984px 148px #FFF , 135px 1402px #FFF , 517px 1336px #FFF , 517px 834px #FFF , 1707px 1169px #FFF , 1076px 1628px #FFF , 1092px 1452px #FFF , 1106px 656px #FFF , 164px 1880px #FFF , 1185px 1801px #FFF , 1919px 222px #FFF , 462px 1622px #FFF , 1287px 1035px #FFF , 527px 1898px #FFF , 1864px 64px #FFF , 559px 363px #FFF , 1761px 1978px #FFF , 681px 626px #FFF , 228px 28px #FFF , 1550px 1191px #FFF , 817px 1019px #FFF , 555px 1640px #FFF , 1234px 254px #FFF , 1233px 227px #FFF , 568px 989px #FFF , 1091px 516px #FFF , 1425px 423px #FFF , 82px 1928px #FFF , 1823px 122px #FFF , 1900px 1947px #FFF , 708px 609px #FFF , 749px 156px #FFF , 195px 492px #FFF , 1899px 947px #FFF , 1166px 1677px #FFF , 1257px 256px #FFF , 1504px 48px #FFF , 599px 686px #FFF , 183px 33px #FFF , 1422px 1149px #FFF , 1058px 453px #FFF , 1077px 108px #FFF , 401px 1574px #FFF , 1490px 29px #FFF , 794px 1610px #FFF , 694px 1563px #FFF , 319px 1629px #FFF , 1772px 1327px #FFF , 276px 358px #FFF , 1491px 1942px #FFF , 1352px 993px #FFF , 722px 82px #FFF , 1530px 1202px #FFF , 1461px 215px #FFF , 338px 765px #FFF , 1266px 865px #FFF , 620px 797px #FFF , 642px 507px #FFF , 403px 1194px #FFF , 592px 97px #FFF , 852px 1094px #FFF , 959px 1927px #FFF , 1040px 386px #FFF , 85px 1729px #FFF , 293px 1108px #FFF , 1623px 1628px #FFF , 1699px 866px #FFF , 719px 1086px #FFF , 1944px 723px #FFF , 780px 39px #FFF , 1626px 634px #FFF , 772px 820px #FFF , 156px 1398px #FFF , 1145px 869px #FFF , 1908px 1441px #FFF , 804px 1855px #FFF , 179px 1862px #FFF , 1095px 1387px #FFF , 1089px 1372px #FFF , 1205px 1653px #FFF , 449px 1816px #FFF , 352px 1721px #FFF , 1846px 180px #FFF , 496px 369px #FFF , 1731px 498px #FFF , 1935px 1836px #FFF , 1567px 909px #FFF , 1486px 1777px #FFF , 539px 1127px #FFF , 891px 1877px #FFF , 64px 687px #FFF , 958px 1961px #FFF , 1403px 757px #FFF , 1206px 199px #FFF , 214px 20px #FFF , 1054px 1910px #FFF , 2px 1611px #FFF , 22px 511px #FFF , 454px 1409px #FFF , 517px 808px #FFF , 1120px 1620px #FFF , 1584px 1570px #FFF , 410px 155px #FFF , 1207px 1397px #FFF , 1589px 1757px #FFF , 24px 132px #FFF , 1891px 83px #FFF , 1872px 758px #FFF , 101px 955px #FFF , 963px 554px #FFF , 588px 856px #FFF , 1098px 1174px #FFF , 292px 981px #FFF , 969px 1191px #FFF , 1163px 729px #FFF , 492px 1190px #FFF , 982px 324px #FFF , 129px 1957px #FFF , 1515px 1011px #FFF , 1234px 109px #FFF , 1601px 828px #FFF , 1221px 1174px #FFF , 1258px 1669px #FFF , 33px 1026px #FFF , 639px 559px #FFF , 1328px 915px #FFF , 1093px 1796px #FFF , 816px 50px #FFF , 823px 1563px #FFF , 702px 37px #FFF , 1948px 1232px #FFF , 1431px 564px #FFF;
}

#stars2 {
    width: 2px;
    height: 2px;
    background: transparent;
    box-shadow: 486px 1478px #FFF , 14px 1327px #FFF , 1169px 868px #FFF , 799px 354px #FFF , 1236px 1458px #FFF , 1871px 1601px #FFF , 372px 1960px #FFF , 375px 1148px #FFF , 957px 1091px #FFF , 1026px 1423px #FFF , 1450px 3px #FFF , 1054px 1124px #FFF , 1654px 1279px #FFF , 1569px 443px #FFF , 1602px 935px #FFF , 157px 916px #FFF , 216px 1312px #FFF , 80px 1840px #FFF , 778px 472px #FFF , 1585px 254px #FFF , 643px 690px #FFF , 1875px 72px #FFF , 145px 1347px #FFF , 1194px 1690px #FFF , 1975px 1641px #FFF , 1361px 1987px #FFF , 718px 459px #FFF , 801px 1797px #FFF , 65px 1719px #FFF , 1804px 1850px #FFF , 1441px 596px #FFF , 1295px 931px #FFF , 1959px 1159px #FFF , 686px 1152px #FFF , 1381px 1855px #FFF , 492px 34px #FFF , 381px 114px #FFF , 1578px 202px #FFF , 1976px 882px #FFF , 122px 795px #FFF , 822px 1193px #FFF , 1172px 1443px #FFF , 972px 1336px #FFF , 757px 81px #FFF , 1119px 420px #FFF , 513px 1330px #FFF , 1617px 444px #FFF , 1415px 1209px #FFF , 1901px 733px #FFF , 1556px 567px #FFF , 503px 979px #FFF , 216px 966px #FFF , 706px 679px #FFF , 1715px 952px #FFF , 61px 1306px #FFF , 717px 483px #FFF , 1268px 815px #FFF , 1767px 1567px #FFF , 1362px 198px #FFF , 950px 655px #FFF , 427px 155px #FFF , 1138px 1553px #FFF , 1077px 715px #FFF , 865px 179px #FFF , 621px 1372px #FFF , 383px 1992px #FFF , 1551px 92px #FFF , 1757px 1393px #FFF , 527px 609px #FFF , 118px 728px #FFF , 721px 1182px #FFF , 498px 857px #FFF , 949px 354px #FFF , 128px 1856px #FFF , 935px 436px #FFF , 970px 1089px #FFF , 201px 1613px #FFF , 182px 1788px #FFF , 996px 346px #FFF , 620px 29px #FFF , 512px 519px #FFF , 1452px 846px #FFF , 1568px 309px #FFF , 1557px 535px #FFF , 1839px 173px #FFF , 902px 819px #FFF , 906px 1879px #FFF , 811px 1314px #FFF , 310px 1581px #FFF , 27px 557px #FFF , 173px 274px #FFF , 818px 289px #FFF , 795px 1020px #FFF , 42px 1297px #FFF , 1006px 111px #FFF , 950px 681px #FFF , 422px 364px #FFF , 1155px 1179px #FFF , 965px 541px #FFF , 690px 960px #FFF , 1449px 1717px #FFF , 446px 1299px #FFF , 66px 234px #FFF , 467px 1048px #FFF , 881px 823px #FFF , 1900px 1723px #FFF , 371px 574px #FFF , 932px 1148px #FFF , 374px 194px #FFF , 557px 74px #FFF , 1115px 690px #FFF , 203px 441px #FFF , 1386px 14px #FFF , 1400px 519px #FFF , 1992px 217px #FFF , 1314px 1813px #FFF , 875px 230px #FFF , 789px 92px #FFF , 1591px 896px #FFF , 1087px 227px #FFF , 650px 1829px #FFF , 1749px 165px #FFF , 1480px 93px #FFF , 242px 477px #FFF , 489px 429px #FFF , 185px 14px #FFF , 1990px 1798px #FFF , 584px 881px #FFF , 1298px 1314px #FFF , 915px 1674px #FFF , 1497px 235px #FFF , 506px 255px #FFF , 104px 1528px #FFF , 1884px 1859px #FFF , 1623px 602px #FFF , 1013px 898px #FFF , 1636px 390px #FFF , 634px 1548px #FFF , 1997px 1839px #FFF , 181px 1704px #FFF , 751px 435px #FFF , 1878px 771px #FFF , 1881px 296px #FFF , 1799px 124px #FFF , 1559px 174px #FFF , 978px 983px #FFF , 101px 720px #FFF , 65px 182px #FFF , 1694px 300px #FFF , 269px 709px #FFF , 85px 198px #FFF , 55px 595px #FFF , 23px 1203px #FFF , 1979px 914px #FFF , 1435px 174px #FFF , 1065px 817px #FFF , 698px 1881px #FFF , 1767px 487px #FFF , 494px 1558px #FFF , 169px 974px #FFF , 297px 930px #FFF , 886px 292px #FFF , 1860px 1832px #FFF , 834px 574px #FFF , 1523px 1517px #FFF , 1922px 455px #FFF , 720px 1074px #FFF , 1508px 680px #FFF , 1875px 922px #FFF , 1168px 1443px #FFF , 172px 341px #FFF , 1878px 1771px #FFF , 110px 1250px #FFF , 217px 1774px #FFF , 461px 478px #FFF , 1441px 1319px #FFF , 456px 533px #FFF , 655px 567px #FFF , 695px 1022px #FFF , 1979px 232px #FFF , 1819px 851px #FFF , 1873px 1561px #FFF , 1145px 159px #FFF , 694px 1472px #FFF , 1033px 501px #FFF , 182px 859px #FFF , 977px 926px #FFF , 1631px 1130px #FFF , 994px 1985px #FFF , 1632px 1684px #FFF , 232px 1053px #FFF , 1968px 930px #FFF , 461px 1937px #FFF , 1501px 525px #FFF , 1074px 1798px #FFF , 1087px 641px #FFF , 1301px 430px #FFF , 1470px 1948px #FFF , 1215px 1475px #FFF , 1100px 919px #FFF;
    animation: animStar 100s linear infinite;
}
#stars2:after {
    content: " ";
    position: absolute;
    top: 2000px;
    width: 2px;
    height: 2px;
    background: transparent;
    box-shadow: 486px 1478px #FFF , 14px 1327px #FFF , 1169px 868px #FFF , 799px 354px #FFF , 1236px 1458px #FFF , 1871px 1601px #FFF , 372px 1960px #FFF , 375px 1148px #FFF , 957px 1091px #FFF , 1026px 1423px #FFF , 1450px 3px #FFF , 1054px 1124px #FFF , 1654px 1279px #FFF , 1569px 443px #FFF , 1602px 935px #FFF , 157px 916px #FFF , 216px 1312px #FFF , 80px 1840px #FFF , 778px 472px #FFF , 1585px 254px #FFF , 643px 690px #FFF , 1875px 72px #FFF , 145px 1347px #FFF , 1194px 1690px #FFF , 1975px 1641px #FFF , 1361px 1987px #FFF , 718px 459px #FFF , 801px 1797px #FFF , 65px 1719px #FFF , 1804px 1850px #FFF , 1441px 596px #FFF , 1295px 931px #FFF , 1959px 1159px #FFF , 686px 1152px #FFF , 1381px 1855px #FFF , 492px 34px #FFF , 381px 114px #FFF , 1578px 202px #FFF , 1976px 882px #FFF , 122px 795px #FFF , 822px 1193px #FFF , 1172px 1443px #FFF , 972px 1336px #FFF , 757px 81px #FFF , 1119px 420px #FFF , 513px 1330px #FFF , 1617px 444px #FFF , 1415px 1209px #FFF , 1901px 733px #FFF , 1556px 567px #FFF , 503px 979px #FFF , 216px 966px #FFF , 706px 679px #FFF , 1715px 952px #FFF , 61px 1306px #FFF , 717px 483px #FFF , 1268px 815px #FFF , 1767px 1567px #FFF , 1362px 198px #FFF , 950px 655px #FFF , 427px 155px #FFF , 1138px 1553px #FFF , 1077px 715px #FFF , 865px 179px #FFF , 621px 1372px #FFF , 383px 1992px #FFF , 1551px 92px #FFF , 1757px 1393px #FFF , 527px 609px #FFF , 118px 728px #FFF , 721px 1182px #FFF , 498px 857px #FFF , 949px 354px #FFF , 128px 1856px #FFF , 935px 436px #FFF , 970px 1089px #FFF , 201px 1613px #FFF , 182px 1788px #FFF , 996px 346px #FFF , 620px 29px #FFF , 512px 519px #FFF , 1452px 846px #FFF , 1568px 309px #FFF , 1557px 535px #FFF , 1839px 173px #FFF , 902px 819px #FFF , 906px 1879px #FFF , 811px 1314px #FFF , 310px 1581px #FFF , 27px 557px #FFF , 173px 274px #FFF , 818px 289px #FFF , 795px 1020px #FFF , 42px 1297px #FFF , 1006px 111px #FFF , 950px 681px #FFF , 422px 364px #FFF , 1155px 1179px #FFF , 965px 541px #FFF , 690px 960px #FFF , 1449px 1717px #FFF , 446px 1299px #FFF , 66px 234px #FFF , 467px 1048px #FFF , 881px 823px #FFF , 1900px 1723px #FFF , 371px 574px #FFF , 932px 1148px #FFF , 374px 194px #FFF , 557px 74px #FFF , 1115px 690px #FFF , 203px 441px #FFF , 1386px 14px #FFF , 1400px 519px #FFF , 1992px 217px #FFF , 1314px 1813px #FFF , 875px 230px #FFF , 789px 92px #FFF , 1591px 896px #FFF , 1087px 227px #FFF , 650px 1829px #FFF , 1749px 165px #FFF , 1480px 93px #FFF , 242px 477px #FFF , 489px 429px #FFF , 185px 14px #FFF , 1990px 1798px #FFF , 584px 881px #FFF , 1298px 1314px #FFF , 915px 1674px #FFF , 1497px 235px #FFF , 506px 255px #FFF , 104px 1528px #FFF , 1884px 1859px #FFF , 1623px 602px #FFF , 1013px 898px #FFF , 1636px 390px #FFF , 634px 1548px #FFF , 1997px 1839px #FFF , 181px 1704px #FFF , 751px 435px #FFF , 1878px 771px #FFF , 1881px 296px #FFF , 1799px 124px #FFF , 1559px 174px #FFF , 978px 983px #FFF , 101px 720px #FFF , 65px 182px #FFF , 1694px 300px #FFF , 269px 709px #FFF , 85px 198px #FFF , 55px 595px #FFF , 23px 1203px #FFF , 1979px 914px #FFF , 1435px 174px #FFF , 1065px 817px #FFF , 698px 1881px #FFF , 1767px 487px #FFF , 494px 1558px #FFF , 169px 974px #FFF , 297px 930px #FFF , 886px 292px #FFF , 1860px 1832px #FFF , 834px 574px #FFF , 1523px 1517px #FFF , 1922px 455px #FFF , 720px 1074px #FFF , 1508px 680px #FFF , 1875px 922px #FFF , 1168px 1443px #FFF , 172px 341px #FFF , 1878px 1771px #FFF , 110px 1250px #FFF , 217px 1774px #FFF , 461px 478px #FFF , 1441px 1319px #FFF , 456px 533px #FFF , 655px 567px #FFF , 695px 1022px #FFF , 1979px 232px #FFF , 1819px 851px #FFF , 1873px 1561px #FFF , 1145px 159px #FFF , 694px 1472px #FFF , 1033px 501px #FFF , 182px 859px #FFF , 977px 926px #FFF , 1631px 1130px #FFF , 994px 1985px #FFF , 1632px 1684px #FFF , 232px 1053px #FFF , 1968px 930px #FFF , 461px 1937px #FFF , 1501px 525px #FFF , 1074px 1798px #FFF , 1087px 641px #FFF , 1301px 430px #FFF , 1470px 1948px #FFF , 1215px 1475px #FFF , 1100px 919px #FFF;
}

#stars3 {
    width: 3px;
    height: 3px;
    background: transparent;
    box-shadow: 1459px 1166px #FFF , 403px 1816px #FFF , 1258px 1244px #FFF , 399px 1269px #FFF , 747px 857px #FFF , 1037px 5px #FFF , 941px 636px #FFF , 726px 1011px #FFF , 312px 1657px #FFF , 655px 1456px #FFF , 1584px 424px #FFF , 1903px 664px #FFF , 777px 1956px #FFF , 1358px 746px #FFF , 1122px 340px #FFF , 1098px 1137px #FFF , 1369px 568px #FFF , 46px 1030px #FFF , 1995px 625px #FFF , 491px 685px #FFF , 1508px 1799px #FFF , 203px 1140px #FFF , 607px 166px #FFF , 235px 369px #FFF , 286px 76px #FFF , 1976px 1724px #FFF , 812px 1544px #FFF , 1951px 306px #FFF , 298px 676px #FFF , 1293px 260px #FFF , 576px 1802px #FFF , 1436px 1393px #FFF , 957px 450px #FFF , 809px 1668px #FFF , 929px 1063px #FFF , 1519px 92px #FFF , 1409px 1781px #FFF , 667px 1364px #FFF , 1965px 1759px #FFF , 1788px 175px #FFF , 499px 1068px #FFF , 1293px 183px #FFF , 741px 318px #FFF , 546px 267px #FFF , 1167px 1783px #FFF , 1132px 1305px #FFF , 421px 134px #FFF , 127px 860px #FFF , 1099px 1337px #FFF , 968px 1333px #FFF , 1666px 750px #FFF , 1439px 255px #FFF , 1480px 1887px #FFF , 1380px 1931px #FFF , 925px 968px #FFF , 1977px 1342px #FFF , 385px 322px #FFF , 1272px 1722px #FFF , 489px 1930px #FFF , 1804px 483px #FFF , 1412px 834px #FFF , 697px 196px #FFF , 994px 815px #FFF , 1261px 1555px #FFF , 875px 1298px #FFF , 1555px 1220px #FFF , 1277px 211px #FFF , 1490px 1980px #FFF , 587px 540px #FFF , 1402px 356px #FFF , 1915px 1163px #FFF , 844px 166px #FFF , 45px 942px #FFF , 1238px 981px #FFF , 503px 946px #FFF , 565px 6px #FFF , 1008px 333px #FFF , 1732px 193px #FFF , 1366px 93px #FFF , 749px 1242px #FFF , 577px 624px #FFF , 1118px 1605px #FFF , 1169px 1419px #FFF , 293px 139px #FFF , 1044px 400px #FFF , 218px 908px #FFF , 1397px 265px #FFF , 638px 1707px #FFF , 512px 260px #FFF , 214px 405px #FFF , 70px 597px #FFF , 1987px 380px #FFF , 1684px 1114px #FFF , 618px 1729px #FFF , 744px 1684px #FFF , 1077px 1059px #FFF , 1714px 407px #FFF , 324px 1742px #FFF , 70px 736px #FFF , 361px 1402px #FFF;
    animation: animStar 150s linear infinite;
}
#stars3:after {
    content: " ";
    position: absolute;
    top: 2000px;
    width: 3px;
    height: 3px;
    background: transparent;
    box-shadow: 1459px 1166px #FFF , 403px 1816px #FFF , 1258px 1244px #FFF , 399px 1269px #FFF , 747px 857px #FFF , 1037px 5px #FFF , 941px 636px #FFF , 726px 1011px #FFF , 312px 1657px #FFF , 655px 1456px #FFF , 1584px 424px #FFF , 1903px 664px #FFF , 777px 1956px #FFF , 1358px 746px #FFF , 1122px 340px #FFF , 1098px 1137px #FFF , 1369px 568px #FFF , 46px 1030px #FFF , 1995px 625px #FFF , 491px 685px #FFF , 1508px 1799px #FFF , 203px 1140px #FFF , 607px 166px #FFF , 235px 369px #FFF , 286px 76px #FFF , 1976px 1724px #FFF , 812px 1544px #FFF , 1951px 306px #FFF , 298px 676px #FFF , 1293px 260px #FFF , 576px 1802px #FFF , 1436px 1393px #FFF , 957px 450px #FFF , 809px 1668px #FFF , 929px 1063px #FFF , 1519px 92px #FFF , 1409px 1781px #FFF , 667px 1364px #FFF , 1965px 1759px #FFF , 1788px 175px #FFF , 499px 1068px #FFF , 1293px 183px #FFF , 741px 318px #FFF , 546px 267px #FFF , 1167px 1783px #FFF , 1132px 1305px #FFF , 421px 134px #FFF , 127px 860px #FFF , 1099px 1337px #FFF , 968px 1333px #FFF , 1666px 750px #FFF , 1439px 255px #FFF , 1480px 1887px #FFF , 1380px 1931px #FFF , 925px 968px #FFF , 1977px 1342px #FFF , 385px 322px #FFF , 1272px 1722px #FFF , 489px 1930px #FFF , 1804px 483px #FFF , 1412px 834px #FFF , 697px 196px #FFF , 994px 815px #FFF , 1261px 1555px #FFF , 875px 1298px #FFF , 1555px 1220px #FFF , 1277px 211px #FFF , 1490px 1980px #FFF , 587px 540px #FFF , 1402px 356px #FFF , 1915px 1163px #FFF , 844px 166px #FFF , 45px 942px #FFF , 1238px 981px #FFF , 503px 946px #FFF , 565px 6px #FFF , 1008px 333px #FFF , 1732px 193px #FFF , 1366px 93px #FFF , 749px 1242px #FFF , 577px 624px #FFF , 1118px 1605px #FFF , 1169px 1419px #FFF , 293px 139px #FFF , 1044px 400px #FFF , 218px 908px #FFF , 1397px 265px #FFF , 638px 1707px #FFF , 512px 260px #FFF , 214px 405px #FFF , 70px 597px #FFF , 1987px 380px #FFF , 1684px 1114px #FFF , 618px 1729px #FFF , 744px 1684px #FFF , 1077px 1059px #FFF , 1714px 407px #FFF , 324px 1742px #FFF , 70px 736px #FFF , 361px 1402px #FFF;
}

@keyframes animStar {
    from {
        transform: translateY(0px);
    }
    to {
        transform: translateY(-2000px);
    }
}

.date-picker {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 8px;
    font-size: 16px;
    color: #374151;
}
</style>
