@extends('layouts.layout')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-question-circle fa-sm text-info"></i>
            Guide des Locations
        </h1>
        <a href="{{ route('locations.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-handshake fa-sm text-white-50"></i> Mes Locations
        </a>
    </div>

    <div class="row">
        <!-- Guide principal -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book-open"></i>
                        Comment fonctionne le système de locations ?
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <p class="lead">BookShare vous permet de louer des livres entre utilisateurs de manière simple et sécurisée.</p>
                            
                            <h5 class="mt-4 mb-3">
                                <i class="fas fa-user-check text-primary"></i>
                                Pour les locataires (ceux qui louent)
                            </h5>
                            
                            <div class="timeline-container mb-4">
                                <div class="step-item">
                                    <div class="step-number bg-primary">1</div>
                                    <div class="step-content">
                                        <h6>Parcourir les livres</h6>
                                        <p>Explorez la bibliothèque et trouvez le livre qui vous intéresse. Cliquez sur "Louer ce livre" s'il est disponible.</p>
                                    </div>
                                </div>
                                
                                <div class="step-item">
                                    <div class="step-number bg-warning">2</div>
                                    <div class="step-content">
                                        <h6>Faire une demande</h6>
                                        <p>Remplissez le formulaire avec vos préférences : dates, durée, lieu de rencontre et prix proposé.</p>
                                    </div>
                                </div>
                                
                                <div class="step-item">
                                    <div class="step-number bg-info">3</div>
                                    <div class="step-content">
                                        <h6>Attendre la confirmation</h6>
                                        <p>Le propriétaire reçoit votre demande et peut l'accepter, la refuser ou négocier les conditions.</p>
                                    </div>
                                </div>
                                
                                <div class="step-item">
                                    <div class="step-number bg-success">4</div>
                                    <div class="step-content">
                                        <h6>Récupérer et profiter</h6>
                                        <p>Une fois acceptée, récupérez le livre au lieu convenu et profitez de votre lecture !</p>
                                    </div>
                                </div>
                                
                                <div class="step-item">
                                    <div class="step-number bg-secondary">5</div>
                                    <div class="step-content">
                                        <h6>Retourner le livre</h6>
                                        <p>Respectez la date de retour convenue pour maintenir une bonne réputation sur la plateforme.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="mt-4 mb-3">
                                <i class="fas fa-user-tie text-success"></i>
                                Pour les propriétaires (ceux qui prêtent)
                            </h5>
                            
                            <div class="timeline-container mb-4">
                                <div class="step-item">
                                    <div class="step-number bg-primary">1</div>
                                    <div class="step-content">
                                        <h6>Recevoir une demande</h6>
                                        <p>Vous recevez une notification quand quelqu'un souhaite louer un de vos livres.</p>
                                    </div>
                                </div>
                                
                                <div class="step-item">
                                    <div class="step-number bg-warning">2</div>
                                    <div class="step-content">
                                        <h6>Examiner la demande</h6>
                                        <p>Consultez les détails : qui, quand, combien de temps, où et à quel prix.</p>
                                    </div>
                                </div>
                                
                                <div class="step-item">
                                    <div class="step-number bg-info">3</div>
                                    <div class="step-content">
                                        <h6>Accepter ou refuser</h6>
                                        <p>Décidez si vous acceptez les conditions proposées. Vous pouvez contacter le demandeur pour négocier.</p>
                                    </div>
                                </div>
                                
                                <div class="step-item">
                                    <div class="step-number bg-success">4</div>
                                    <div class="step-content">
                                        <h6>Organiser la remise</h6>
                                        <p>Contactez le locataire pour organiser la remise du livre, puis démarrez la location.</p>
                                    </div>
                                </div>
                                
                                <div class="step-item">
                                    <div class="step-number bg-secondary">5</div>
                                    <div class="step-content">
                                        <h6>Récupérer le livre</h6>
                                        <p>À la fin de la période, récupérez votre livre et terminez la location.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statuts des locations -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i>
                        Comprendre les statuts
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="status-item mb-3">
                                <span class="badge badge-warning mr-2">En attente</span>
                                <span>La demande a été envoyée et attend une réponse du propriétaire.</span>
                            </div>
                            
                            <div class="status-item mb-3">
                                <span class="badge badge-info mr-2">Confirmée</span>
                                <span>Le propriétaire a accepté la demande. La location peut commencer.</span>
                            </div>
                            
                            <div class="status-item mb-3">
                                <span class="badge badge-success mr-2">En cours</span>
                                <span>La location est active. Le livre a été remis au locataire.</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="status-item mb-3">
                                <span class="badge badge-secondary mr-2">Terminée</span>
                                <span>La location est terminée. Le livre a été retourné.</span>
                            </div>
                            
                            <div class="status-item mb-3">
                                <span class="badge badge-dark mr-2">Annulée</span>
                                <span>La demande a été refusée ou annulée.</span>
                            </div>
                            
                            <div class="status-item mb-3">
                                <span class="badge badge-danger mr-2">En retard</span>
                                <span>La date de retour prévue est dépassée.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Conseils et FAQ -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-lightbulb"></i>
                        Conseils pour réussir
                    </h6>
                </div>
                <div class="card-body">
                    <div class="tip-item mb-3">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Soyez précis</strong><br>
                        <small class="text-muted">Indiquez clairement le lieu et l'heure de rencontre.</small>
                    </div>
                    
                    <div class="tip-item mb-3">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Prix équitable</strong><br>
                        <small class="text-muted">Proposez un prix raisonnable selon la durée et la valeur du livre.</small>
                    </div>
                    
                    <div class="tip-item mb-3">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Respectez les délais</strong><br>
                        <small class="text-muted">Retournez les livres à temps pour maintenir votre réputation.</small>
                    </div>
                    
                    <div class="tip-item mb-3">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Communiquez</strong><br>
                        <small class="text-muted">N'hésitez pas à contacter l'autre partie en cas de problème.</small>
                    </div>
                    
                    <div class="tip-item mb-0">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Prenez soin des livres</strong><br>
                        <small class="text-muted">Traitez les livres empruntés avec le même soin que les vôtres.</small>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-question-circle"></i>
                        Questions fréquentes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="faq-item mb-3">
                        <strong>Puis-je modifier ma demande ?</strong><br>
                        <small class="text-muted">Oui, tant qu'elle n'a pas été acceptée ou refusée.</small>
                    </div>
                    
                    <div class="faq-item mb-3">
                        <strong>Que se passe-t-il si je suis en retard ?</strong><br>
                        <small class="text-muted">Contactez le propriétaire pour négocier une extension.</small>
                    </div>
                    
                    <div class="faq-item mb-3">
                        <strong>Puis-je annuler une location ?</strong><br>
                        <small class="text-muted">Oui, mais seulement avant qu'elle ne commence.</small>
                    </div>
                    
                    <div class="faq-item mb-0">
                        <strong>Comment fixer le prix ?</strong><br>
                        <small class="text-muted">Considérez la durée, la rareté du livre et les prix du marché.</small>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-rocket"></i>
                        Commencer maintenant
                    </h6>
                </div>
                <div class="card-body text-center">
                    <p class="mb-3">Prêt à commencer votre première location ?</p>
                    <a href="{{ route('books.index') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-search"></i> Parcourir les livres
                    </a>
                    <a href="{{ route('locations.index') }}" class="btn btn-outline-primary btn-block">
                        <i class="fas fa-list"></i> Mes locations
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-container {
    position: relative;
}

.step-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    position: relative;
}

.step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 20px;
    top: 40px;
    width: 2px;
    height: calc(100% + 20px);
    background: #e3e6f0;
    z-index: 0;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    margin-right: 15px;
    position: relative;
    z-index: 1;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
    padding-top: 5px;
}

.step-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.step-content p {
    margin-bottom: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.status-item {
    display: flex;
    align-items: flex-start;
}

.tip-item, .faq-item {
    border-left: 3px solid #e3e6f0;
    padding-left: 10px;
}

.tip-item {
    border-left-color: #28a745;
}

.faq-item {
    border-left-color: #ffc107;
}
</style>
@endsection
