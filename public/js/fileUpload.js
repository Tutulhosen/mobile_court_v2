!function(t){function e(l){if(n[l])return n[l].exports;var i=n[l]={i:l,l:!1,exports:{}};return t[l].call(i.exports,i,i.exports,e),i.l=!0,i.exports}var n={};e.m=t,e.c=n,e.d=function(t,n,l){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:l})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="",e(e.s=205)}({205:function(t,e,n){t.exports=n(206)},206:function(t,e){fileUpload=t.exports={context:null,regex:null,sFiles:[],init:function(){$(document).on("change",".multiFileInputCls",function(t){t.preventDefault();var e=t.target.files,n="",l=!1,i=e[0].name.split(".").pop().toLowerCase();if(-1!=$.inArray(i,["gif","png","jpg","jpeg","bmp"])&&(n="IMAGE",l=!0),-1!=$.inArray(i,["doc","docx","pdf","xlsx","xlsm","xltx","xltm","txt"])&&(n="DOCUMENT",l=!0),!l)return $.alert("ফাইল  ফরম্যাট গ্রহণযোগ্য  না ","ধন্যবাদ"),$(this).parents(".multiRowContainer").remove(),!1;if(fileUpload.validateFileSize(e[0])){if("IMAGE"==n)for(var o=0;o<e.length;o++){var r=new Image;r.src=URL.createObjectURL(e[o]),r.setAttribute("class","img-responsive img-thumbnail multi-image"),r.setAttribute("width","60px"),r.setAttribute("height","60px"),$(this).parents(".multiRowContainer").find(".col2").html(r)}"DOCUMENT"==n&&$(this).parents(".multiRowContainer").find(".col2").html(' <img src="/doc1.png" alt="DOC" class="img-responsive img-thumbnail" width="60px" height="60px" />')}else $.alert("  গ্রহণযোগ্য  ফাইল  সাইজ   ৫   মেগাবাইট এর  কম !","অবহতিকরণ বার্তা"),$(this).parents(".multiRowContainer").remove()}),$(document).on("click",".photoDelete",function(t){t.preventDefault(),$(this).parents(".multiRowContainer").remove()}),$(document).on("click",".multifileupload",function(t){t.preventDefault();var e=$(".photoContainer"),n=Math.random(),l=$('<div class="clearfix form-group multiRowContainer"></div>'),i=$('<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col1"></div>'),o=$('<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col2"></div>'),r=$('<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col3"></div>'),a=$('<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col4"></div>'),c=$('<label class="btn btn-primary custom-file-upload"><i class="glyphicon glyphicon-upload"></i> </label>').attr("for",n),s=$('<input class="multiFileInputCls hidden "/>').attr("type","file").attr("name","files[][someName]").attr("id",n),p=c.append(s);return i.append(p),r.append('<input name="captions[][someCaption]" type="text" class="form-control" id="newNumber" placeholder=" সংযুক্তির  ধরন ">'),a.append('<button type="button" class="btn btn-danger photoDelete"><i class="glyphicon glyphicon-remove"></i></button>'),l.append(i,o,r,a),e.append(l),!1})},validateFileSize:function(t){return!(t.size>5e6)}},$(document).ready(function(){fileUpload.init()})}});