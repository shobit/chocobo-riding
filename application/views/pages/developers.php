<style>
pre {
    margin: 20px 30px;
    padding: 15px;
    border: 5px solid #dddddd;
    background: #eeeeee;
    color: #444444;
    font-size: 12px;
}

strong , b {
    background: #f7f8cd;
    padding: 2px 6px;
    color: #514226;
}
</style>

<div class="nav">
	<?php echo html::anchor('#/presentation', 'Présentation') ?>
	<?php
	if ( ! empty($user->api))
	{ 
		echo html::anchor('#/api', 'API');
	}
	?>
</div>

<div class="section" id="presentation">

	<div style="height:1px;"></div>

	<?php $server = url::base() ?>
	
	<p>Les appels à l’API doivent être faits sous la forme de requêtes HTTP en GET ou en POST sur le domaine :</p>

	<pre><?php echo $server ?></pre>
	
	<p>Le format de retour par défaut est JSON. Si vous voulez du XML, sous devez le spécifier en le mentionnant dans la requête :</p>
	
	<pre>&parse=xml</pre>
	
	<p>Voici un exemple de retour JSON :</p>
	
	<pre>{ “success” : (bool), “errors” : [], ...}</pre>
	
	<p><ul>
		<li>success est un booléen qui renvoie true si la requête a été effectuée sans erreurs, false sinon.</li>
		<li>errors contient l’ensemble des codes erreurs rencontrés, vide sinon.</li>
		<li>Selon la requête, il peut y avoir d'autres champs placés au même niveau que success et errors.</li>
	</ul></p>
	
	<h3>Clé API</h3>
	
	<p>Pour toutes vos requêtes, vous devez mentionner votre clé API dans la requête, avec le paramètre key.</p>
	
	<pre>&key=<?php echo htmlentities('<key>'); ?></pre>
	
	<p>Ce paramètre est obligatoire et est unique à chaque développeur. Pour obtenir une clé API, vous devez envoyer votre demande par message privé à un administrateur.</p>
	
	<h3>Token user</h3>
	
	<p>Lorsque vous avez besoin d’interagir avec un compte user, vous devez d’abord identifier ce user avec la fonction <b>/user/auth</b>. Une fois l’identification effectuée, vous récupérez un token qui vous servira dans toutes les requêtes suivantes, à placer en paramètre.</p>
	
	<pre>&token=<?php echo htmlentities('<token>'); ?></pre>
	
	<p>Vous pouvez à tout moment détruire le token avec <b>/user/destroy</b>.</p>
	
	<h3>Conditions d’utilisation</h3>
	
	<p>Il y a quelques règles à respecter pour que cette API fonctionne au mieux :</p>
	
	<p><ul>
	<li>Vous devez mentionner l'utilisation de Chocobo Riding (avec l'URL du site) sur votre application.</li>
	<li>Vous ne devez pas faire plus de requêtes que nécessaire.</li>
	<li>Vous ne devez pas proposer d'application payante.</li>
	<li>Vous devez rester joignable via la messagerie de votre compte de jeu</li>
	</ul></p>
	
	<p>En utilisant l'API avec votre clé, vous acceptez implicitement ces conditions d'utilisation.</p>

</div>

<?php if ( ! empty($user->api)): ?>
<div class="section" id="api">

	<div style="height:1px;"></div>

	<!-- USER auth -->
	<h3>/user/auth</h3>
	
	<pre>GET <?php echo $server; ?>user/auth?login=<?php echo htmlentities('<login>'); ?>&password=<?php echo htmlentities('<sha1>'); ?></pre>
	
	<p>Identifie le user avec son login et le hash sha1. Retourne le token à utiliser pour les requêtes futures.</p>
	
	<h3>/user/destroy</h3>
	
	<pre>GET <?php echo $server; ?>user/destroy</pre>
	
	<p>Détruit instantanément le token spécifié.</p>
	
	<!-- USER infos -->
	<h3>/user/infos</h3>
	
	<pre>GET <?php echo $server; ?>user/infos[/<?php echo htmlentities('<login>'); ?>]</pre>
	
	<p>Renvoie les informations principales du membre identifié ou d'un autre membre (l'accès varie selon les options vie privée de chaque membre).</p>
	
	<pre>
user:
	string login : nom du user
	string avatar : url complète de l'image du user
	array notifications
		int comments : commentaires non lus
		int messages : messages non lus
	</pre>
	
	<!-- CHOCOBO list -->
	<h3>/chocobo/liste</h3>
	
	<pre>GET <?php echo $server; ?>chocobo/liste[/<?php echo htmlentities('<login>'); ?>]</pre>
	
	<p>Renvoie la liste des chocobos appartenant au membre identifié ou d'un autre membre.</p>
	
	<pre>
chocobos: chocobo*

chocobo:
	string name : nom du chocobo
	int level : niveau du chocobo,
	string classe : classe du chocobo,
	int speed : vitesse <i>de base</i> du chocobo,
	int endur : endurance <i>de base</i> du chocobo,
	int intel : intelligence <i>de base</i> du chocobo,
	int rage : rage <i>de base</i> du chocobo,
	</pre>

	<!-- CHOCOBO view -->
	<h3>/chocobo/view</h3>
	
	<pre>GET <?php echo $server; ?>chocobo/view/<?php echo htmlentities('<name>'); ?></pre>
	
	<p>Renvoie les informations relatives à un chocobo.</p>
	
	<pre>
chocobo:
	string name : nom du chocobo,
	int race_id : ID de la course actuelle, 
	int gender : ID du genre du chocobo
	int job : ID du job du chocobo
	string classe : classe du chocobo,
	int colour : ID de la couleur du chocobo, 
	int level : niveau du chocobo,
	int lvl_limit : niveau maximum du chocobo, 
	int xp : expérience sur 100 du chocobo,
	int points : points restants à répartir dans les caractéristiques du chocobo, 
	float fame : côte du chocobo, 
	int speed : vitesse <i>de base</i> du chocobo,
	int intel : intelligence <i>de base</i> du chocobo,
	int endur : endurance <i>de base</i> du chocobo,
	int rage : rage <i>de base</i> du chocobo,
	int pl : PL <i>de base</i> du chocobo,
	int hp : HP <i>de base</i> du chocobo,
	int mp : MP <i>de base</i> du chocobo,
	float max_speed : vitesse record du chocobo,
	int nb_races : nombre de courses faites du chocobo,
	int mated : timestamp du dernier accouplement du chocobo,
	int nb_mated : nombre d'accouplements du chocobo,
	int birthday : date de la naissance du chocobo,
	</pre>

</div>
<?php endif; ?>

<script>
$(function(){

	init_nav('#/presentation');

});
</script>