<?php
class user_bolstats {
	function main ($content) {
		$mysqli = new mysqli("localhost", "blumenbach", "t??sven*+9", "BlumenbachOnline_productive");
		if ($mysqli->connect_errno) {
			return "Connect failed: " . $mysqli->connect_error;
		}
		
		$result = $mysqli->query("SELECT count(uid) as cnt FROM `tx_bolonline_Kerndaten`");		
		$content .= 'Kerndatenobjekte (grün): ' . $result->fetch_object()->cnt . '<br/>';
		$result->close();
		
		$result = $mysqli->query("SELECT count(id) as cnt FROM `tx_bolonline_PartII`");		
		$content .= 'Einzelobjekte (blau): ' . $result->fetch_object()->cnt . '<br/>';
		$result->close();
		
		$result = $mysqli->query("SELECT count(id) as cnt FROM `tx_bolonline_Mediafiles`");
		$nm = $result->fetch_object()->cnt;
		$content .= '<br/>Mediafiles (an Kerndaten): ' . $nm . '<br/>';
		$result->close();
		
		$result = $mysqli->query("SELECT count(id) as cnt FROM `tx_bolonline_Mediafiles_PartI`");
		$nmi = $result->fetch_object()->cnt;		
		$content .= 'Mediafiles Part I: ' . $nmi . '<br/>';
		$result->close();	
		
		$result = $mysqli->query("SELECT count(id) as cnt FROM `tx_bolonline_Mediafiles_PartII`");
		$nmii = $result->fetch_object()->cnt;			
		$content .= 'Mediafiles Part II: ' . $nmii . '<br/>';
		$result->close();
		
		$result = $mysqli->query("SELECT count(id) as cnt FROM `tx_bolonline_Mediafiles_PartIII`");
		$nmiii = $result->fetch_object()->cnt;				
		$content .= 'Mediafiles Part III: ' . $nmiii . '<br/>';
		$result->close();
		
		$result = $mysqli->query("SELECT count(id) as cnt FROM `tx_bolonline_Mediafiles_PartIV`");
		$nmiiii = $result->fetch_object()->cnt;				
		$content .= 'Mediafiles Part IV: ' . $nmiiii . '<br/>';
		$result->close();
		
		$content .= 'Sum Mediafiles: ' . ($nm + $nmi + $nmii + $nmiii +  $nmiiii).'<br/><br/>';
		
		$content .= '<strong>Mediafiles</strong><br/> ';
		
		$union_query = 'SELECT count(id) as cnt from (
							SELECT id, file_uri  FROM `tx_bolonline_Mediafiles` UNION  
							SELECT id, file_uri  FROM `tx_bolonline_Mediafiles_PartI` UNION
							SELECT id, file_uri  FROM `tx_bolonline_Mediafiles_PartII` UNION
							SELECT id, file_uri  FROM `tx_bolonline_Mediafiles_PartIII` UNION
							SELECT id, file_uri  FROM `tx_bolonline_Mediafiles_PartIV`
						) as tmp';
		
		$result = $mysqli->query($union_query);
		$content .= 'all: ' . $result->fetch_object()->cnt . '<br/>';
		$result->close();	
		
	    $result = $mysqli->query($union_query." WHERE file_uri REGEXP '.swf$'");
		$content .= 'swf: ' . $result->fetch_object()->cnt . '<br/>';
		$result->close();
		
		$result = $mysqli->query($union_query." WHERE file_uri REGEXP '.(jpg|jpeg)$'");
		$content .= 'jpg/jpeg: ' . $result->fetch_object()->cnt . '<br/>';
		$result->close();	
		
		$result = $mysqli->query($union_query." WHERE file_uri REGEXP '.tif$'");
		$content .= 'tif: ' . $result->fetch_object()->cnt . '<br/>';
		$result->close();	
		
		$result = $mysqli->query($union_query." WHERE file_uri REGEXP '.gif$'");
		$content .= 'gif: ' . $result->fetch_object()->cnt . '<br/>';
		$result->close();	

		$result = $mysqli->query($union_query." WHERE file_uri NOT REGEXP '.(jpg|jpeg|swf|tif|gif)$'");
		$content .= 'other: ' . $result->fetch_object()->cnt . '<br/><br/>';
		$result->close();
		
		$result = $mysqli->query("SELECT file_uri as uri from (
							SELECT id, file_uri  FROM `tx_bolonline_Mediafiles` UNION  
							SELECT id, file_uri  FROM `tx_bolonline_Mediafiles_PartI` UNION
							SELECT id, file_uri  FROM `tx_bolonline_Mediafiles_PartII` UNION
							SELECT id, file_uri  FROM `tx_bolonline_Mediafiles_PartIII` UNION
							SELECT id, file_uri  FROM `tx_bolonline_Mediafiles_PartIV`
						) as tmp WHERE file_uri NOT REGEXP '.(jpg|jpeg|swf|tif|gif)$'");

		while ($obj = $result->fetch_object()) {						
			$content .= 'other object: ' . $obj->uri . '<br/>';
		}
		$result->close();
		
		$mysqli->close();
		return $content;
	}
}
?>
