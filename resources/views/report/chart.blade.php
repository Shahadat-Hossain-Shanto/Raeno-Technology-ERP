<!DOCTYPE html>
<html>
   <head>
      <title>jQuery HighchartTable Plugin</title>
      <meta charset="UTF-8">

      <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
      <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
      <script src="{{ asset('plugins/chart.js/highcharts.js') }}"></script>
      <style>
         body
         {
         font-family: Arial;
         text-align:center;
         }
         #containerDivID{
         width:550px;
         height:auto;
         }
      </style>
   </head>
   <body>
      <h1 style="color:green">GeeksforGeeks</h1>
      <b>jQuery HighchartTable Plugin</b>
      <div class="containerDivID">
         <table class="highchart"
            data-graph-container-before="1"
            data-graph-type="column">
            <thead>
               <tr>
                  <th>Month</th>
                  <th>Expenditure</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td>January</td>
                  <td>18000</td>
               </tr>
               <tr>
                  <td>February</td>
                  <td>15000</td>
               </tr>
               <tr>
                  <td>March</td>
                  <td>13000</td>
               </tr>
               <tr>
                  <td>April</td>
                  <td>22000</td>
               </tr>
               <tr>
               <td>May</td>
               <td>12000</td>
               </tr>
               <tr>
               <td>June</td>
               <td>12000</td>
               </tr>
            </tbody>
         </table>    
      </div>
   
      <script>
      $(document).ready(function() {
         $('table.highchart').highchartTable();
      });
   
      </script>
   </body>
</html>
