{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent} 
    <!-- d3 test -->
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
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help=''}       
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            
            <div id="chart"></div>
        </div>
    </div>
</section>
{/block}
    
    
{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}