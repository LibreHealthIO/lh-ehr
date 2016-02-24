/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var opener_list=[];

function set_opener(window,opener)
{
    top.opener_list[window]=opener;
}

function get_opener(window)
{
    return top.opener_list[window];
}