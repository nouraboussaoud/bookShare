<!-- Active Polls Section for Event Show Page -->
<div style="background: #fff; border-radius: 0.6rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h3 style="font-size: 1.3rem; font-weight: 700; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-chart-pie" style="color: #667eea;"></i>
            📊 Sondages actifs
        </h3>
        @can('manageMembership', $event->readingGroup)
            <a href="{{ route('polls.create', [$event->readingGroup, $event]) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1.25rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 0.5rem; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: all 0.2s; hover-background: #5568d3;">
                <i class="fas fa-plus"></i>
                Créer un sondage
            </a>
        @endcan
    </div>

    @if($event->activePolls()->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
            @foreach($event->activePolls()->get() as $poll)
                <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.25rem; transition: all 0.2s;">
                    <!-- Poll Header -->
                    <div style="display: flex; align-items: start; justify-content: space-between; margin-bottom: 0.75rem; gap: 0.75rem;">
                        <h4 style="font-weight: 700; color: #1f2937; margin: 0; flex: 1; font-size: 1rem;">{{ $poll->title }}</h4>
                        <span style="display: inline-block; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 700; background: #d1fae5; color: #065f46; border-radius: 2rem; white-space: nowrap;">
                            {{ $poll->votes()->count() }} vote(s)
                        </span>
                    </div>

                    <!-- Description -->
                    @if($poll->description)
                        <p style="font-size: 0.9rem; color: #6b7280; margin: 0.75rem 0;">{{ Str::limit($poll->description, 100) }}</p>
                    @endif

                    <!-- Poll Type Badge -->
                    <div style="margin: 0.75rem 0;">
                        @if($poll->type === 'yes_no')
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; font-size: 0.75rem; background: #dbeafe; color: #1d4ed8; border-radius: 0.25rem;">👍 Oui/Non</span>
                        @elseif($poll->type === 'multiple_choice')
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; font-size: 0.75rem; background: #e9d5ff; color: #6d28d9; border-radius: 0.25rem;">🎯 Choix multiples</span>
                        @elseif($poll->type === 'rating')
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; font-size: 0.75rem; background: #fef3c7; color: #78350f; border-radius: 0.25rem;">⭐ Évaluation</span>
                        @endif
                    </div>

                    <!-- Quick Preview -->
                    @php
                        $results = $poll->getResults();
                    @endphp

                    @if($poll->type === 'rating' && isset($results['average']))
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 0.75rem; border-radius: 0.5rem; margin: 0.75rem 0; text-align: center;">
                            <div style="font-size: 1.5rem; font-weight: 700;">{{ round($results['average'], 1) }}/5</div>
                            <div style="font-size: 0.8rem; opacity: 0.9;">Note moyenne</div>
                        </div>
                    @else
                        <div style="margin: 0.75rem 0; font-size: 0.9rem;">
                            @php
                                $topResults = array_slice($results['data'], 0, 2, true);
                            @endphp
                            @foreach($topResults as $result)
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span style="color: #374151; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $result['text'] }}</span>
                                    <span style="color: #6b7280; font-weight: 600; margin-left: 0.5rem;">{{ $result['percentage'] }}%</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Action Link -->
                    <a href="{{ route('polls.show', [$event->readingGroup, $event, $poll]) }}" style="display: block; text-align: center; padding: 0.75rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; text-decoration: none; border-radius: 0.5rem; font-size: 0.9rem; font-weight: 600; transition: all 0.2s; margin-top: 1rem;">
                        <i class="fas fa-arrow-right me-1"></i>Voir le sondage
                    </a>
                </div>
            @endforeach
        </div>
    @else
        @can('manageMembership', $event->readingGroup)
            <div style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border: 1px solid #dbeafe; border-radius: 0.5rem; padding: 2rem; text-align: center;">
                <i class="fas fa-inbox" style="font-size: 2rem; color: #667eea; margin-bottom: 0.75rem; display: block;"></i>
                <p style="color: #6b7280; margin: 0.75rem 0;">Aucun sondage actif pour le moment.</p>
                <p style="color: #9ca3af; font-size: 0.9rem; margin-bottom: 1rem;">Créez le premier sondage pour impliquer votre groupe !</p>
            </div>
        @endcan
    @endif
</div>
</div>
