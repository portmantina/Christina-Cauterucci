
<head>
<style>
#floatingbar {
background: #000;
height: 57px;
width: 100%;
position: absolute;
top: 0px; 
}

.body {
margin: 0;
background: #ff3333;
height: 3500px;
font-family:’Arbutus Slab’, serif;
color: rgb(191,191,191);
}

#toplogo
{
position: fixed;
top: 0px;
left: 50%;
margin-left: -42px; /* next line offset */
z-index: 1001;
} 

</style>
</head>

<body>


<div id="floatingbar"> </div> 

<div id="toplogo"> <a href= "http://www.pacifichoists.com.au/wp-content/uploads/2012/05/accessories-84x84.jpg"> </a> </div>

<script type=”text/ javascript” src=”http:// code.jquery.com/jquery- 1.9.1.min.js”>
// < ![CDATA[ 
 <script type="text/javascript">
$('#floatingbar').
scrollToFixed();
</script>
</body>