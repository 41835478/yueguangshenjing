/*
* @Author: Marte
* @Date:   2017-09-26 11:37:42
* @Last Modified by:   Marte
* @Last Modified time: 2017-09-26 11:37:57
*/

'use strict';
$(function(){
        $('.title li').click(function(){
            $(this).find('a').addClass('active');
            $(this).siblings().find('a').removeClass('active');
        });
    });