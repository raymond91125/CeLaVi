<!DOCTYPE html>
<meta charset="utf-8">
<!-- JS scripts -->

	<head>
		<meta charset="UTF-8">
		<title>Cell lineage visualisation</title>
		<!-- Custom CSS styles -->
		<link href="style_2.css" rel="stylesheet" type="text/css" > 
		<link rel="stylesheet" href="./lib/d3-context-menu.css" />
				<script src="./lib/d3.v3.js"></script> 
			<!--	<script src="https://d3js.org/d3.v3.min.js"></script> -->
       <script src="./lib/d3.v4.min.js"></script>  <!--3d cells-->
        <script src="./lib/d3-3d.min.js"></script>
        <script src="./lib/jquery.min.js"></script>
        <script src="./lib/newick.js" type="text/javascript"></script>
        <script src="./lib/d3-hierarchy.min.js"></script>
        <script src="./lib/d3-context-menu.js"></script>
        <script src="https://d3js.org/d3-color.v1.min.js"></script>
        <script src="https://d3js.org/d3-interpolate.v1.min.js"></script>
				<script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
				<!-- Range slider code -->
				<script src="./lib/d3RangeSlider.js"></script>
				<!-- Range slider style -->
				<link href="./lib/d3RangeSlider.css" rel="stylesheet">
    		<!-- Bootstrap -->
				<link href="./lib/bootstrap.min.css" rel="stylesheet">
				<!--Plotly-->
				<script src="./lib//plotly-latest.min.js"></script>


    </head>
    <body>
		<div id= "container", class="svg-container">
			<h3> Cell lineage interactive visualisation</h3>
			<h6> <i> by Irepan Salvador-Martinez </i> </h6>

			<form onsubmit="Submit_Function()" method="submit" enctype="multipart/form-data">
      <h6 class="pl-0">INPUT FILES</h6>
			<div class="row">
        <div class="col-sm-3 mb-2">
          <a href="#" data-toggle="tooltip" data-placement="right" title="Input file cell lineage tree with or without branch lengths."><label for="temp">Tree file:</label></a>
          <div class="custom-file mb-3" id="temp">
            <input type="file" class="custom-file-input" id="JSON_uploader" name="TREE_FILE">
            <label class="custom-file-label" for="JSON_uploader">Upload tree (select format)</label>
          </div>
				</div>
				<div class="col-sm-3 my-auto" id="INPUT_tree">
          <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="Newick_TREE" name="Tree_INPUT" value="newick" checked>
            <label class="custom-control-label" for="Newick_TREE">Newick file</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="Json_TREE" name="Tree_INPUT" value="json">
            <label class="custom-control-label" for="Json_TREE">JSON file</label>
					</div>
					<div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="Json_CLONES" name="Tree_INPUT" value="clones">
            <label class="custom-control-label" for="Json_CLONES">CLONES file(json)</label>
          </div>

        </div>

				<div class="col-sm-3 mb-2">
          <a href="#" data-toggle="tooltip" data-placement="left" title="Reads a csv file with 4 columns: The first column is the cell ID (same as in the tree), and the other columns are coordinates X, Y and Z "><label for="temp">Coords file:</label></a>
          <div class="custom-file mb-3" id="temp">
            <input type="file" class="custom-file-input" id="3Dcoord_uploader" name="coordsfile">
            <label class="custom-file-label" for="3Dcoord_uploader">Input coordinates file</label>
          </div>
				</div>
				<div class="col-sm-3 my-auto">
            <!-- <button type="submit" class="btn btn-primary" align="right" onclick="validateNum()">Submit</button> -->
           <!-- <button type="submit" class="btn btn-primary" align="right">Submit</button> -->
        </div>
      </div>
			</form>
          
      <div class="status" align="Center" >Click on a cell      </div>
     	<!-- Separate Areas -->
			<div id="area1"> <!-- cell lineage -->
            <h4>Cell lineage</h4>
			</div>
            
			<div id="area2" class="svg-container-inbox2"> <!-- cells in 3d -->
				<h4> Cells in 3D</h4>
				</div>
        <!-- LEFT PART OF THE CONTROLS, FOR THE TREE  -->
				<div id= "controls_1a" align="left" class="controls">
					<div class="row">
						<div class="col-sm-8 mb-2">
						<a href="#" data-toggle="tooltip" data-placement="left" title="Play with tree">Cell lineage tree controls:</a>
						<div class="row">
							<div class="col-sm-2.5" align="right">
								<div class="btn-group">
  								<button id="zoom_in_tree" type="button" class="btn btn-success"><></button>
									<button id="zoom_out_tree" type="button" class="btn btn-success">><</button>
  								<button id="pan_down_tree" type="button" class="btn btn-success">v</button>
  								<button id="pan_up_tree" type="button" class="btn btn-success">^</button>
								</div>
							</div>
							<div class="col-sm-1.5">
								<button id="BranchLenghts" type="button" onclick="show_bl()" class="btn btn-success" >Show BL</button> 
							</div>
							<div class="col-sm-1.5">
								<button id="CollapseAll" type="button" onclick="collapseAll()" class="btn btn-success" >Collapse All</button> 
							</div>
							<div class="col-sm-1.5">
								<button id="Reset"  type="button" onclick="resetAll()" class="btn btn-success" >Reset Topology</button> 
							</div>
							<div class="col-sm-1.5">
								<button id="Reset_cols_Tree"  type="button" class="btn btn-success" >Reset cols</button> 
							</div>
							<div class="col-sm-2">
								<label class="checkbox"><input id="Tree_checkbox" type="checkbox" value="">Show Descendants</label>
							</div>

						<div class="col-sm-2 my-auto" id="OPTIONS_tree">
          		<div class="custom-control custom-radio">
            		<input type="radio" class="custom-control-input" id="Abs_BL" name="Abs_Rel" value="abs" checked>
            		<label class="custom-control-label" for="Abs_BL">Abs BL</label>
          		</div>
          		<div class="custom-control custom-radio">
            		<input type="radio" class="custom-control-input" id="Rel_BL" name="Abs_Rel" value="rel">
            		<label class="custom-control-label" for="Rel_BL">Rel BL</label>
          		</div>
						</div>

							<div class "col-sm-1" id="controls_1">
							</div>
						</div>
						</div>
					<!-- RIGHT PART OF THE CONTROLS, FOR CELLS IN 3D -->
						<div class="col-sm-4 mb-2">
						<a href="#" data-toggle="tooltip" data-placement="right" title="Zoom and Pan cells in 3D">3D cells controls:</a>
						<div class="row">
							<div class="col-sm-3.5">
								<button id="reset" type="button" class="btn btn-info">Reset cols</button>
							</div>
							<div class="col-sm-3">
								<input type="number" class="form-control" id="CellSize" placeholder="" value="19" min="1" step="1" title="Size for rendering cells"  required>
							</div>
								<div class="col-sm-3">
								<input type="number" class="form-control" id="CellStroke" placeholder="" value="2" min="1" step="1" max="5" title="Stroke width"  required>
							</div>
							<div class="col-sm-2.5">
								<input id="Cells_checkbox" type="checkbox" title="This option works better if the tree is completely expanded" value="">Show Lineage
							</div>
						</div>
					</div>
				</div>        
		</div> 
        <div id="slider">
           <h5>Tree depth</h5>
        </div>
        <div id="HM_scale">
           <h5>Lineage relationships</h5>
        </div>
        

		<div id="footer">
			<strong>Source</strong> : https://github.com/irepansalvador/
		</div>
		<!-- JS Libraries -->
		<!--<script src="d3.js" charset="utf-8"></script>-->

 
        <!-- Custom JS code -->
		<!--<script src="cells_3d_paryhale.js"></script> -->
				<script src="cell_lineage_v4_paryhale.js"></script>
 				<script src="plotly_test.js"></script>
	       <script src="brush_paryhale.js"></script>
        <script src="Nested_rels_scale.js"></script>
				<script>Coords_upload_button("3Dcoord_uploader", load_dataset_2)</script>
				<script>Tree_upload_button("JSON_uploader"); </script>
				<script src="clones_slider.js"></script>
<!--
		<script>
		function Submit_Function() {
			var uploader = document.getElementById("JSON_uploader");  
			var file = uploader.files[0];

			var reader = new FileReader();
			reader.onload = function(e) {
				var contents = e.target.result;
					load_dataset_json(contents)};
			reader.readAsText(file);
		};

		</script>
     //   Run functions to initialise the visualisation
        <script>
            init();
            //tree_from_New();
            processData(data,0);
            reset_cell_cols();
          //  console.log("here?");
        </script>
-->        
	</body>
