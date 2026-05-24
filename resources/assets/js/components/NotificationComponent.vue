<template>
   
</template>

<script>
    export default {
        mounted() {
            Echo.channel(`call-center.${userId}`)
                .listen('.call-center', (data) => {
                    console.log('event fired')
                    window.open(data.route);
                     iziToast.info({
                        timeout: 0,
                        transitionIn: 'flipInX',
                        transitionOut: 'flipOutX',
                        position:'bottomLeft',
                        rtl:true,
                        message: 'اشعار عميل كول سنتر',
                         overlay: true,
                        // onClosing: function () {
                                // var audio = document.getElementById('audio');
                                // audio.muted = false;
                                // audio.play();
                        //     // window.open(data.route);
                        // },
                        onOpening: function () {
                               var audio = document.getElementById('audio');
                                audio.muted = false;
                                audio.play();
                            // window.open(data.route);
                        },
                    });

                   
            });


           

            console.log('Component mounted.')
        }
    }
</script>
