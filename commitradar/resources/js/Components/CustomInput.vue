<template>
    <div>
        <div id="poda">
            <div class="glow"></div>
            <div class="darkBorderBg"></div>
            <div class="darkBorderBg"></div>
            <div class="darkBorderBg"></div>
            <div class="white"></div>
            <div class="border"></div>

            <div id="main">
                <input
                    v-model="repositoryUrl"
                    @keydown.enter="handleInput"
                    placeholder="Paste the link of the repository..."
                    type="text"
                    class="input"
                />
                <div id="input-mask"></div>
                <div id="pink-mask"></div>
                <div class="filterBorder"></div>
                <div id="filter-icon"  @click="handleInput">
                    <svg
                        height="96"
                        width="98"
                        viewBox="0 0 98 96"
                        fill="none"
                    >
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.854 0C21.839 0 0 22 0 49.217c0 21.756 13.993 40.172 33.405 46.69 2.427.49 3.316-1.059 3.316-2.362 0-1.141-.08-5.052-.08-9.127-13.59 2.934-16.42-5.867-16.42-5.867-2.184-5.704-5.42-7.17-5.42-7.17-4.448-3.015.324-3.015.324-3.015 4.934.326 7.523 5.052 7.523 5.052 4.367 7.496 11.404 5.378 14.235 4.074.404-3.178 1.699-5.378 3.074-6.6-10.839-1.141-22.243-5.378-22.243-24.283 0-5.378 1.94-9.778 5.014-13.2-.485-1.222-2.184-6.275.486-13.038 0 0 4.125-1.304 13.426 5.052a46.97 46.97 0 0 1 12.214-1.63c4.125 0 8.33.571 12.213 1.63 9.302-6.356 13.427-5.052 13.427-5.052 2.67 6.763.97 11.816.485 13.038 3.155 3.422 5.015 7.822 5.015 13.2 0 18.905-11.404 23.06-22.324 24.283 1.78 1.548 3.316 4.481 3.316 9.126 0 6.6-.08 11.897-.08 13.526 0 1.304.89 2.853 3.316 2.364 19.412-6.52 33.405-24.935 33.405-46.691C97.707 22 75.788 0 48.854 0z" fill="#fff"/>
                    </svg>
                </div>
                <div id="search-icon">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke-linejoin="round"
                        stroke-linecap="round"
                        height="24"
                        fill="none"
                        class="feather feather-search"
                    >
                        <circle stroke="url(#search)" r="8" cy="11" cx="11"></circle>
                        <line
                            stroke="url(#searchl)"
                            y2="16.65"
                            y1="22"
                            x2="16.65"
                            x1="22"
                        ></line>
                        <defs>
                            <linearGradient gradientTransform="rotate(50)" id="search">
                                <stop stop-color="#f8e7f8" offset="0%"></stop>
                                <stop stop-color="#b6a9b7" offset="50%"></stop>
                            </linearGradient>
                            <linearGradient id="searchl">
                                <stop stop-color="#b6a9b7" offset="0%"></stop>
                                <stop stop-color="#837484" offset="50%"></stop>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CustomInput",
    data() {
        return {
            repositoryUrl: '', // Holds the user input
        };
    },
    methods: {
        handleInput() {
            const today = new Date().toISOString().slice(0, 10);

            if (new Date(this.dateFrom) > new Date(this.dateTo)) {
                this.notificationHandler('Start date cannot be after end date.');
                return;
            }

            if (this.dateFrom > today || this.dateTo > today) {
                this.notificationHandler('Dates cannot be in the future.');
                return;
            }

            if (this.validateUrl(this.repositoryUrl)) {
                // Extract owner and repo from the repository URL
                const { owner, repo } = this.extractOwnerAndRepo(this.repositoryUrl);

                if (owner && repo) {
                    console.log('Valid owner and repo:', owner, repo);
                    console.log('Redirecting to /github/redirect with owner and repo');

                    // Perform a hard redirect to /github/redirect with owner and repo as query parameters
                    window.location.href = `/github/redirect?owner=${encodeURIComponent(owner)}&repo=${encodeURIComponent(repo)}&startDate=${this.dateFrom}&endDate=${this.dateTo}`;
                } else {
                    this.notificationHandler('Invalid repository URL. Could not extract owner and repo.');
                }
            } else {
                // Trigger the parent's notification if URL is invalid
                this.notificationHandler('Invalid repository URL. Please try again.');
            }
        },
        validateUrl(url) {
            // Basic validation to check for a valid GitHub repository URL
            const githubRepoPattern = /^https?:\/\/github\.com\/[^\/]+\/[^\/]+$/;
            return githubRepoPattern.test(url);
        },
        extractOwnerAndRepo(url) {
            // Extract owner and repo from a valid GitHub repository URL using a regex
            const match = url.match(/^https?:\/\/github\.com\/([^\/]+)\/([^\/]+)/);
            if (match && match.length === 3) {
                return { owner: match[1], repo: match[2].replace(/\.git$/, '') }; // Remove .git if it exists
            }
            return { owner: null, repo: null };
        },
    },
    props: {
        notificationHandler: Function, // Prop to trigger the parent's notification
        dateFrom: String,
        dateTo: String,
    },
};
</script>



<style scoped>
.white, .border, .darkBorderBg, .glow {
    max-height: 70px;
    max-width: 314px;
    height: 100%;
    width: 100%;
    position: absolute;
    overflow: hidden;
    z-index: -1;
    /* Border Radius */
    border-radius: 12px;
    filter: blur(3px);
}
.input {
    background-color: #010201;
    border: none;
    /* padding:7px; */
    width: 550px;
    height: 56px;
    border-radius: 10px;
    color: #fdfbd4;
    padding-inline: 59px;
    font-size: 18px;
}
#poda {
    display: flex;
    align-items: center;
    justify-content: center;
    user-select: none;
}
.input::placeholder {
    color: #c0b9c0;
}

.input:focus {
    outline: none;
}

#main:focus-within > #input-mask {
    display: none;
}

#input-mask {
    pointer-events: none;
    width: 480px;
    height: 20px;
    position: absolute;
    background: linear-gradient(90deg, transparent, black);
    top: 18px;
    left: 70px;
}
#pink-mask {
    pointer-events: none;
    width: 30px;
    height: 20px;
    position: absolute;
    background: #cf30aa;
    top: 10px;
    left: 5px;
    filter: blur(20px);
    opacity: 0.8;
    /*animation:leftright 4s ease-in infinite;*/
    transition: all 2s;
}
#main:hover > #pink-mask {
    /*animation: rotate 4s linear infinite;*/
    opacity: 0;
}

.white {
    max-height: 63px;
    max-width: 560px;
    border-radius: 10px;
    filter: blur(2px);
}

.white::before {
    content: "";
    z-index: -2;
    text-align: center;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(83deg);
    position: absolute;
    width: 600px;
    height: 600px;
    background-repeat: no-repeat;
    background-position: 0 0;
    filter: brightness(1.4);
    background-image: conic-gradient(
        rgba(0, 0, 0, 0) 0%,
        #a099d8,
        rgba(0, 0, 0, 0) 8%,
        rgba(0, 0, 0, 0) 50%,
        #dfa2da,
        rgba(0, 0, 0, 0) 58%
    );
     /*animation: rotate 4s linear infinite;*/
    transition: all 2s;
}
.border {
    max-height: 59px;
    max-width: 555px;
    border-radius: 11px;
    filter: blur(0.5px);
}
.border::before {
    content: "";
    z-index: -2;
    text-align: center;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(70deg);
    position: absolute;
    width: 600px;
    height: 600px;
    filter: brightness(1.3);
    background-repeat: no-repeat;
    background-position: 0 0;
    background-image: conic-gradient(
        #1c191c,
        #402fb5 5%,
        #1c191c 14%,
        #1c191c 50%,
        #cf30aa 60%,
        #1c191c 64%
    );
    /*animation: rotate 4s 0.1s linear infinite;*/
    transition: all 2s;
}
.darkBorderBg {
    max-height: 65px;
    max-width: 560px;
}
.darkBorderBg::before {
    content: "";
    z-index: -2;
    text-align: center;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(82deg);
    position: absolute;
    width: 600px;
    height: 600px;
    background-repeat: no-repeat;
    background-position: 0 0;
    background-image: conic-gradient(
        rgba(0, 0, 0, 0),
        #18116a,
        rgba(0, 0, 0, 0) 10%,
        rgba(0, 0, 0, 0) 50%,
        #6e1b60,
        rgba(0, 0, 0, 0) 60%
    );
    transition: all 2s;
}
#poda:hover > .darkBorderBg::before {
    transform: translate(-50%, -50%) rotate(262deg);
}
#poda:hover > .glow::before {
    transform: translate(-50%, -50%) rotate(240deg);
}
#poda:hover > .white::before {
    transform: translate(-50%, -50%) rotate(263deg);
}
#poda:hover > .border::before {
    transform: translate(-50%, -50%) rotate(250deg);
}

#poda:hover > .darkBorderBg::before {
    transform: translate(-50%, -50%) rotate(-98deg);
}
#poda:hover > .glow::before {
    transform: translate(-50%, -50%) rotate(-120deg);
}
#poda:hover > .white::before {
    transform: translate(-50%, -50%) rotate(-97deg);
}
#poda:hover > .border::before {
    transform: translate(-50%, -50%) rotate(-110deg);
}

#poda:focus-within > .darkBorderBg::before {
    transform: translate(-50%, -50%) rotate(442deg);
    transition: all 4s;
}
#poda:focus-within > .glow::before {
    transform: translate(-50%, -50%) rotate(420deg);
    transition: all 4s;
}
#poda:focus-within > .white::before {
    transform: translate(-50%, -50%) rotate(443deg);
    transition: all 4s;
}
#poda:focus-within > .border::before {
    transform: translate(-50%, -50%) rotate(430deg);
    transition: all 4s;
}

.glow {
    overflow: hidden;
    filter: blur(30px);
    opacity: 0.4;
    max-height: 130px;
    max-width: 560px;
}
.glow:before {
    content: "";
    z-index: -2;
    text-align: center;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(60deg);
    position: absolute;
    width: 999px;
    height: 999px;
    background-repeat: no-repeat;
    background-position: 0 0;
    /*border color, change middle color*/
    background-image: conic-gradient(
        #000,
        #402fb5 5%,
        #000 38%,
        #000 50%,
        #cf30aa 60%,
        #000 87%
    );
    /* change speed here */
    /*animation: rotate 4s 0.3s linear infinite;*/
    transition: all 4s;
}

@keyframes rotate {
    100% {
        transform: translate(-50%, -50%) rotate(450deg);
    }
}
@keyframes leftright {
    0% {
        transform: translate(0px, 0px);
        opacity: 1;
    }

    49% {
        transform: translate(250px, 0px);
        opacity: 0;
    }
    80% {
        transform: translate(-40px, 0px);
        opacity: 0;
    }

    100% {
        transform: translate(0px, 0px);
        opacity: 1;
    }
}

#filter-icon {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    max-height: 40px;
    max-width: 38px;
    height: 100%;
    width: 100%;

    isolation: isolate;
    overflow: hidden;
    /* Border Radius */
    border-radius: 10px;
    background: linear-gradient(180deg, #161329, black, #1d1b4b);
    border: 1px solid transparent;
    cursor: pointer
}
.filterBorder {
    height: 42px;
    width: 40px;
    position: absolute;
    overflow: hidden;
    top: 7px;
    right: 7px;
    border-radius: 10px;
}

.filterBorder::before {
    content: "";

    text-align: center;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(90deg);
    position: absolute;
    width: 600px;
    height: 600px;
    background-repeat: no-repeat;
    background-position: 0 0;
    filter: brightness(1.35);
    background-image: conic-gradient(
        rgba(0, 0, 0, 0),
        #3d3a4f,
        rgba(0, 0, 0, 0) 50%,
        rgba(0, 0, 0, 0) 50%,
        #3d3a4f,
        rgba(0, 0, 0, 0) 100%
    );
    animation: rotate 4s linear infinite;
}
#main {
    position: relative;
}
#search-icon {
    position: absolute;
    left: 20px;
    top: 15px;
}
</style>
