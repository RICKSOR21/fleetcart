<div
    x-data="ScrollToTop"
    class="scroll-to-top"
    :class="{ active: scrolled }"
    @click="scrollToTop"
>
    <div class="top-arrow">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.293,1.293a1,1,0,0,1,.325-.216.986.986,0,0,1,.764,0,1,1,0,0,1,.325.216l8,8a1,1,0,0,1-1.414,1.414L13,4.414V22a1,1,0,0,1-2,0V4.414L4.707,10.707A1,1,0,0,1,3.293,9.293Z"/>
        </svg>
    </div>

    <svg> 
        <circle
            class="text-gray-300"
            stroke-width="1.5"
            stroke="currentColor"
            fill="transparent"
            r="19"
            cx="20"
            cy="20"
        />
        <circle
            class="text-blue-600"
            stroke-width="1.5"
            :stroke-dasharray="circumference"
            :stroke-dashoffset="circumference - percent / 100 * circumference"
            stroke-linecap="round"
            stroke="currentColor"
            fill="transparent"
            r="19"
            cx="20"
            cy="20"
        />
    </svg>
</div>
