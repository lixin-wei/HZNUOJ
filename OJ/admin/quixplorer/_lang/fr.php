<?php

// French Language Module for v2.3 (translated by Olivier Pariseau & the QuiX project)

$GLOBALS["charset"] = "iso-8859-1";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "d/m/Y H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "ERREUR(S)",
	"back"			=> "Page précédente",
	
	// root
	"home"			=> "Le répertoire home n'existe pas, vérifiez vos préférences.",
	"abovehome"		=> "Le répertoire courant n'a pas l'air d'etre au-dessus du répertoire home.",
	"targetabovehome"	=> "Le répertoire cible n'a pas l'air d'etre au-dessus du répertoire home.",
	
	// exist
	"direxist"		=> "Ce répertoire n'existe pas.",
	//"filedoesexist"	=> "Ce fichier existe deja.",
	"fileexist"		=> "Ce fichier n'existe pas.",
	"itemdoesexist"		=> "Cet item existe deja.",
	"itemexist"		=> "Cet item n'existe pas.",
	"targetexist"		=> "Le répertoire cible n'existe pas.",
	"targetdoesexist"	=> "L'item cible existe deja.",
	
	// open
	"opendir"		=> "Impossible d'ouvrir le répertoire.",
	"readdir"		=> "Impossible de lire le répertoire.",
	
	// access
	"accessdir"		=> "Vous n'etes pas autorisé a acceder a ce répertoire.",
	"accessfile"		=> "Vous n'etes pas autorisé a accéder a ce fichier.",
	"accessitem"		=> "Vous n'etes pas autorisé a accéder a cet item.",
	"accessfunc"		=> "Vous ne pouvez pas utiliser cette fonction.",
	"accesstarget"		=> "Vous n'etes pas autorisé a accéder au repertoire cible.",
	
	// actions
	"permread"		=> "Lecture des permissions échouée.",
	"permchange"		=> "Changement des permissions échoué.",
	"openfile"		=> "Ouverture du fichier échouée.",
	"savefile"		=> "Sauvegarde du fichier échouée.",
	"createfile"		=> "Création du fichier échouée.",
	"createdir"		=> "Création du répertoire échouée.",
	"uploadfile"		=> "Envoie du fichier échoué.",
	"copyitem"		=> "La copie a échouée.",
	"moveitem"		=> "Le déplacement a échoué.",
	"delitem"		=> "La supression a échouée.",
	"chpass"		=> "Le changement de mot de passe a échoué.",
	"deluser"		=> "La supression de l'usager a échouée.",
	"adduser"		=> "L'ajout de l'usager a échouée.",
	"saveuser"		=> "La sauvegarde de l'usager a échouée.",
	"searchnothing"		=> "Vous devez entrez quelquechose à chercher.",
	
	// misc
	"miscnofunc"		=> "Fonctionalité non disponible.",
	"miscfilesize"		=> "La taille du fichier excède la taille maximale autorisée.",
	"miscfilepart"		=> "L'envoi du fichier n'a pas été complété.",
	"miscnoname"		=> "Vous devez entrer un nom.",
	"miscselitems"		=> "Vous n'avez sélectionné aucuns item(s).",
	"miscdelitems"		=> "Êtes-vous certain de vouloir supprimer ces \"+num+\" item(s)?",
	"miscdeluser"		=> "Êtes-vous certain de vouloir supprimer l'usager '\"+user+\"'?",
	"miscnopassdiff"	=> "Le nouveau mot de passe est indentique au précédent.",
	"miscnopassmatch"	=> "Les mots de passe diffèrent.",
	"miscfieldmissed"	=> "Un champs requis n'a pas été rempli.",
	"miscnouserpass"	=> "Nom d'usager ou mot de passe invalide.",
	"miscselfremove"	=> "Vous ne pouvez pas supprimer votre compte.",
	"miscuserexist"		=> "Ce nom d'usager existe déjà.",
	"miscnofinduser"	=> "Usager non trouvé.",
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "CHANGER LES PERMISSIONS",
	"editlink"		=> "ÉDITER",
	"downlink"		=> "TÉLÉCHARGER",
	"uplink"		=> "PARENT",
	"homelink"		=> "HOME",
	"reloadlink"		=> "RAFRAÎCHIR",
	"copylink"		=> "COPIER",
	"movelink"		=> "DÉPLACER",
	"dellink"		=> "SUPPRIMER",
	"comprlink"		=> "ARCHIVER",
	"adminlink"		=> "ADMINISTRATION",
	"logoutlink"		=> "DÉCONNECTER",
	"uploadlink"		=> "ENVOYER",
	"searchlink"		=> "RECHERCHER",
	
	// list
	"nameheader"		=> "Nom",
	"sizeheader"		=> "Taille",
	"typeheader"		=> "Type",
	"modifheader"		=> "Modifié",
	"permheader"		=> "Perm's",
	"actionheader"		=> "Actions",
	"pathheader"		=> "Chemin",
	
	// buttons
	"btncancel"		=> "Annuler",
	"btnsave"		=> "Sauver",
	"btnchange"		=> "Changer",
	"btnreset"		=> "Réinitialiser",
	"btnclose"		=> "Fermer",
	"btncreate"		=> "Créer",
	"btnsearch"		=> "Chercher",
	"btnupload"		=> "Envoyer",
	"btncopy"		=> "Copier",
	"btnmove"		=> "Déplacer",
	"btnlogin"		=> "Connecter",
	"btnlogout"		=> "Déconnecter",
	"btnadd"		=> "Ajouter",
	"btnedit"		=> "Éditer",
	"btnremove"		=> "Supprimer",
	
	// actions
	"actdir"		=> "Répertoire",
	"actperms"		=> "Changer les permissions",
	"actedit"		=> "Éditer le fichier",
	"actsearchresults"	=> "Résultats de la recherche",
	"actcopyitems"		=> "Copier le(s) item(s)",
	"actcopyfrom"		=> "Copier de /%s à /%s ",
	"actmoveitems"		=> "Déplacer le(s) item(s)",
	"actmovefrom"		=> "Déplacer de /%s à /%s ",
	"actlogin"		=> "Connecter",
	"actloginheader"	=> "Connecter pour utiliser QuiXplorer",
	"actadmin"		=> "Administration",
	"actchpwd"		=> "Changer le mot de passe",
	"actusers"		=> "Usagers",
	"actarchive"		=> "Archiver le(s) item(s)",
	"actupload"		=> "Envoyer le(s) fichier(s)",
	
	// misc
	"miscitems"		=> "Item(s)",
	"miscfree"		=> "Disponible",
	"miscusername"		=> "Usager",
	"miscpassword"		=> "Mot de passe",
	"miscoldpass"		=> "Ancien mot de passe",
	"miscnewpass"		=> "Nouveau mot de passe",
	"miscconfpass"		=> "Confirmer le mot de passe",
	"miscconfnewpass"	=> "Confirmer le nouveau mot de passe",
	"miscchpass"		=> "Changer le mot de passe",
	"mischomedir"		=> "Répertoire home",
	"mischomeurl"		=> "URL home",
	"miscshowhidden"	=> "Voir les items cachés",
	"mischidepattern"	=> "Cacher pattern",
	"miscperms"		=> "Permissions",
	"miscuseritems"		=> "(nom, répertoire home, Voir les items cachés, permissions, actif)",
	"miscadduser"		=> "ajouter un usager",
	"miscedituser"		=> "editer l'usager '%s'",
	"miscactive"		=> "Actif",
	"misclang"		=> "Langage",
	"miscnoresult"		=> "Aucun résultats.",
	"miscsubdirs"		=> "Rechercher dans les sous-répertoires",
	"miscpermnames"		=> array("Lecture seulement","Modifier","Changement le mot de passe","Modifier & Changer le mot de passe",
					"Administrateur"),
	"miscyesno"		=> array("Oui","Non","O","N"),
	"miscchmod"		=> array("Propriétaire", "Groupe", "Publique"),
);
?>