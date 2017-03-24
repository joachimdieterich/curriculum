{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent} 
    <!-- d3 test -->
    
    {if ($chart eq 'institutions') OR ($chart eq 'curriculum')}
    <script src="{$media_url}scripts/d3.v3.min.js"></script>
    {literal}
    <script>
    var nodes2 = [];
    var width = $('#content-wrapper').width(),
        height = window.innerHeight,
        root;
        
    var leafColor = d3.scale.category20();

    var force = d3.layout.force()
        .linkDistance(40)
        .charge(-700)
        .gravity(0.1)
        .size([width, height])
        .on("tick", tick);
    var parentschildren=[];
    var svg = d3.select("#chart").append("svg")
    .attr("width", width)
    .attr("height", height);
    
    var linkedByIndex = {}; //?
		
    var link = svg.selectAll(".link"),
        node = svg.selectAll(".node");
        
    /*d3.json("chart.json", function(error, json) {
      root = json;
      update();
      simulateClick ();
    });*/
    
    root = {/literal}{$map}{literal};
    update();
    simulateClick ();
    
    function simulateClick(){
        $.fn.d3Click = function (i) {
            this.each(function (i, e) {

            var evt = document.createEvent("MouseEvents");
            evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            console.log(e);
            e.dispatchEvent(evt);
            evt.stopPropagation();
          });
        };
  
        console.log(nodes2)
        var newNodes = [];
        for(i=nodes2.length; i>=0; i--){
          newNodes.push(nodes2[i])
        }
        console.log(nodes2.length)
  
        for(i=0;i<newNodes.length;i++){
            if(newNodes[i]){
                $('#'+newNodes[i]).d3Click();
            }
        }

        $('#PARENT').d3Click();
    }
    function update(d) {
      
      var nodes = flatten(root),
          links = d3.layout.tree().links(nodes);

      // Restart the force layout.
      force
          .nodes(nodes)
          .links(links)
          .start();

      // Update links.
      link = link.data(links, function(d) { return d.target.id; });

      link.exit().remove();

      link.enter().insert("line", ".node")
          .attr("class", "link");

      // Update nodes.
      node = node.data(nodes, function(d) { return d.id; });

      // Exit any old links.
      node.exit().remove();
      
      // Enter any new nodes.
      var nodeEnter = node.enter().append("g")
          .attr("class", "node")
          .on("click", click)
          .call(force.drag)
          .attr("href", function(d) { return d.link; });

      nodeEnter.append("svg:a")
      //.attr("xlink:href", function(d){return d.link;})
      .append("circle").attr('id', function(d){ return d.name})
          .attr("r", function(d) { return Math.sqrt(d.size) / 15 || 2.5; });

      nodeEnter.append("text")
          .attr("dy", "0.35em")
          .text(function(d) { return d.name; });

      node.select("circle")
          .style("fill", color);
          
          
          if(parentschildren.length<1){
          node.filter(function(d){
            //console.log(d.name)
            return d.name === 'PARENT'
          }).each(function(d){
            for(i=0;i<d.children.length;i++){
              parentschildren.push(d.children[i].name);
            }
          });
          }
          console.log(parentschildren)  
    }
    

    function tick(d) {
      // sets the bounding box
      node.attr("cx", function(d) { return d.x = Math.max(15, Math.min(width - 15, d.x)); })
      .attr("cy", function(d) { return d.y = Math.max(15, Math.min(height - 15, d.y)); });

      link.attr("x1", function(d) { return d.source.x; })
      .attr("y1", function(d) { return d.source.y; })
      .attr("x2", function(d) { return d.target.x; })
      .attr("y2", function(d) { return d.target.y; });

      node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
    }

    function color(d) {
       return leafColor(d.parentName)
    }
    
    function neighboring(a, b) {
      return a.index == b.index || linkedByIndex[a.index + "," + b.index];
    }

    // Toggle children on click.
    function click(d) {
      if (d3.event.defaultPrevented) return; // ignore drag
      if (d.children) {
        d._children = d.children;
        d.children = null;
      } else {
        d.children = d._children;
        d._children = null;
      }
      update();
      
      // marks the clicked node
      d3.selectAll(".link").transition().duration(500)
      .style("stroke-width", function(o) {
        return o.target === d || o.target === d ? 3 : 1;
      }).style("stroke", function(o) {
        return o.target === d || o.target === d ? "red" : "grey";
      });
      
      d3.selectAll(".node").transition().duration(500)
        .style("stroke-width", function(o) {
           return neighboring(d, o) ? 1 : 0;
        }).style("stroke", function(o) {
           return neighboring(d, o) ? "red" : "white";
        });
    }

    // Returns a list of all nodes under the root.
    function flatten(root) {
      var nodes = [], i = 0;

      function recurse(node) {
        if (node.children) {
          nodes2.push(node.name)
          node.children.forEach(recurse);
        }
        if (!node.id) node.id = ++i;
        nodes.push(node);
    }
  
      recurse(root);
      console.log(nodes2)
      return nodes;
    }
    </script>{/literal} 
    {else}
        <script src="{$media_url}scripts/d3.v4.min.js"></script>
        {literal}
        <script>
        var margin = {top: 20, right: 20, bottom: 30, left: 40},
            width = $('#content-wrapper').width() - margin.left - margin.right - margin.left - margin.right,
            height = 600;

        var parseDate = d3.timeParse("%Y-%m-%d");

        var x = d3.scaleTime().range([0, width]),
            y = d3.scaleLinear().range([height, 0]);

        var xAxis = d3.axisBottom(x),
            yAxis = d3.axisLeft(y);

        var zoom = d3.zoom()
            .scaleExtent([1, 32])
            .translateExtent([[0, 0], [width, height]])
            .extent([[0, 0], [width, height]])
            .on("zoom", zoomed);

        var area = d3.area()
            .curve(d3.curveMonotoneX)
            .x(function(d) { return x(d.date); })
            .y0(height)
            .y1(function(d) { return y(d.total); });
        var svg = d3.select("#chart").append("svg");
        svg.attr("width", $('#content-wrapper').width() - margin.left - margin.right);
        svg.attr("height",height + margin.top + margin.bottom);
        
        
        svg.append("defs").append("clipPath")
            .attr("id", "clip")
          .append("rect")
            .attr("width", width)
            .attr("height", height);

        var g = svg.append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        d3.csv("{/literal}{$map}{literal}", type, function(error, data) {
          if (error) throw error;

          x.domain(d3.extent(data, function(d) { return d.date; }));
          y.domain([0, d3.max(data, function(d) { return d.total; })]);

          g.append("path")
              .datum(data)
              .attr("class", "area")
              .attr("d", area);

          g.append("g")
              .attr("class", "axis axis--x")
              .attr("transform", "translate(0," + height + ")")
              .call(xAxis);

          g.append("g")
              .attr("class", "axis axis--y")
              .call(yAxis);

          var oneYearAgo = new Date();
              oneYearAgo.setDate(oneYearAgo.getDate() - 365);
          var today = Date.now();
          var d0 = new Date(oneYearAgo),
              d1 = new Date(today);
              
          // Gratuitous intro zoom!
          svg.call(zoom).transition()
              .duration(1500)
              .call(zoom.transform, d3.zoomIdentity
                  .scale(width / (x(d1) - x(d0)))
                  .translate(-x(d0), 0));
        });

        function zoomed() {
          var t = d3.event.transform, xt = t.rescaleX(x);
          g.select(".area").attr("d", area.x(function(d) { return xt(d.date); }));
          g.select(".axis--x").call(xAxis.scale(xt));
        }

        function type(d) {
          d.date = parseDate(d.date);
          d.total = +d.total;
          return d;
        }

        </script>
        {/literal}
    {/if}
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}
    <style>
.area {
  fill: steelblue;
  clip-path: url(#clip);
}

</style>
{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help=''}       
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="{if $chart eq 'institutions'}active{/if}"><a href="index.php?action=statistic&chart=institutions" >Institutionen</a></li>
                    <li class="{if $chart eq 'curriculum'}active{/if}"><a href="index.php?action=statistic&chart=curriculum"  >Lehrpläne</a></li>
                    <li class="{if $chart eq 'usage'}active{/if}"><a href="index.php?action=statistic&chart=usage" >Aktivität</a></li>
                    <li class="{if $chart eq 'accomplished'}active{/if}"><a href="index.php?action=statistic&chart=accomplished" >Erfolge</a></li>
                    <li class="{if $chart eq 'newUsers'}active{/if}"><a href="index.php?action=statistic&chart=newUsers" >Neue Nutzer</a></li>
                    <li class="{if $chart eq 'newCurricula'}active{/if}"><a href="index.php?action=statistic&chart=newCurricula" >Neue Lehrpläne</a></li>
                    <li class="{if $chart eq 'newGroups'}active{/if}"><a href="index.php?action=statistic&chart=newGroups" >Neue Lerngruppen</a></li>
                    <li class="{if $chart eq 'newMessages'}active{/if}"><a href="index.php?action=statistic&chart=newMessages" >Neue Nachrichten</a></li>
                    <li class="{if $chart eq 'acceptTerms'}active{/if}"><a href="index.php?action=statistic&chart=acceptTerms" >Nutzungsbedingungen akzeptiert</a></li>
                    <li class="{if $chart eq 'lastlLogin'}active{/if}"><a href="index.php?action=statistic&chart=lastlLogin" >Letzter login (Alle Nutzer)</a></li>
                </ul>
                <div id="chart" style="margin-top: 10px;"></div>
                {*<div class="tab-content">
                    <div class="tab-pane active" id="f_context_1"></div>
                    <div class="tab-pane " id="f_context_2"></div>
                </div>*}
            </div>
            
        </div>
    </div>
</section>
{/block}
    
    
{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}