@foreach ($acoes as $acao)
    <div class="row linha-table d-flex align-items-center justify-content-start">
        <div title="{{ $acao->titulo }}" class="col-5 titulo-span text-start"><span
                class="spacing-col">{{ $acao->titulo }}</span></div>
        <div class="col-1 text-center"><span>{{ $acao->tipo_natureza->natureza->descricao }}</span></div>
        <div title="{{ $acao->tipo_natureza->descricao }}" class="col-2 text-center titulo-span">
            <span>{{ $acao->tipo_natureza->descricao }}</span>
        </div>

        @if ($acao->observacao_gestor)
            <div class="col-1 text-center link titulo-span tag tag-{{ $acao->status }}"><span><a data-toggle="modal" data-target="#modal-parecer{{ $acao->id }}">{{ $acao->status }}</a></span></div>

        @else
            <div class="col-1 text-center tag tag-{{ $acao->status }}"><span>{{ $acao->status }}</span></div>
        @endif

        <div class="col-1 text-center">

            <span>
                @if ($acao->anexo != null)
                    <a href="{{ route('anexo.download', ['acao_id' => $acao->id]) }}" title="Baixar Anexo">
                        <img src="/images/acoes/listView/anexo.svg" alt="Visualizar">
                    </a>
                @endif
            </span>
        </div>
        <div class="col-2 d-flex align-items-center justify-content-center gap-2">
            @if(Auth::user()->perfil_id == 3)
                <span><a href="{{ route('listar.colaboradores', ['acaoId' => $acao->id]) }}"><img
                            src="/images/acoes/listView/person-gear.svg" alt="Colaboradores"
                            title="Colaboradores"></a></span>
            @endif
            <span><a data-toggle="modal" data-target="#modal-info{{ $acao->id }}"><img
                        src="/images/acoes/listView/eye.svg" alt="Visualizar dados" title="Visualizar Ação"></a></span>
            <span><a href="{{ Route('atividade.index', ['acao_id' => $acao->id]) }}"><img
                        src="/images/acoes/listView/atividade.svg" alt="Atividades" title="Atividades"></a></span>
            @if ($acao->status == null || $acao->status == 'Devolvida')
                @unless ($acao->atividades()->has('certificados')->exists())
                    <span><a href="{{ Route('acao.edit', ['acao_id' => $acao->id]) }}"><img
                                src="/images/acoes/listView/editar.svg" alt="Editar" title="Editar Ação"></a></span>
                    <span><a onclick="return confirm('Você tem certeza que deseja excluir esta ação?')"
                            href="{{ Route('acao.delete', ['acao_id' => $acao->id]) }}" title="Excluir Ação"><img
                                src="/images/acoes/listView/lixoIcon.svg" alt="Excluir"></a></span>
                @endunless
                @if (Auth::user()->perfil_id == 3)
                    <span>
                        <a href="{{ Route('gestor.gerar_certificados', ['acao_id' => $acao->id]) }}"
                            onclick="return confirm('Você tem certeza que deseja emitir os certificados?')">
                            <img src="/images/acoes/listView/submeter.svg" alt="emitir certificados"
                                title="Emitir Certificados">
                        </a>
                    </span>
                @else
                    <span><a onclick="return confirm('Você tem certeza que deseja submeter esta ação?')"
                            href="{{ Route('acao.submeter', ['acao_id' => $acao->id]) }}"><img
                                src="/images/acoes/listView/submeter.svg" alt="submeter"
                                title="Submeter Ação"></a></span>
                @endif
            @elseif($acao->status == 'Aprovada')
                <a href="{{ route('certificados.download', ['acao_id' => $acao->id]) }}"><img
                        src="/images/acoes/listView/zipcertificados.svg" alt="" title="Baixar Certificados"></a>

                <a href="{{ route('certificados.lembrete', ['acao_id' => $acao->id]) }}"
                    onclick="return confirm('Enviar lembrete aos integrantes da ação {{ $acao->titulo }}?')">
                    <img src="/images/acoes/listView/lembrete_certificado.svg" alt=""
                        title="Lembrete Certificados Disponíveis">
                </a>

                <!-- <a href="{{ route('certificados.deletar', ['acao_id' => $acao->id]) }}"><img src="/images/acoes/listView/zipcertificados.svg" alt="" title="Deletar Certificados"></a> -->
            @endif
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modal-parecer{{ $acao->id }}">

        <div class="modal-dialog modal-dialog-centered" role="dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #972E3F; color: white;">
                    <h5 class="modal-title"><b>Parecer do Status da Ação</b></h5>
                </div>

                <div class="modal-body">

                    <h5>Observações do Gestor</h5>
                    <span>{{ $acao->observacao_gestor }}</span>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-info{{ $acao->id }}">

        <div class="modal-dialog modal-dialog-centered" role="dialog">

            <div class="modal-content">
                <div class="modal-header" style="background: #972E3F; color: white;">
                    <h5 class="modal-title"><b>Detalhes da Ação Institucional</b></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <span><strong>Título: </strong>{{ $acao->titulo }}</span>
                        <span><b>Natureza: </b>{{ $acao->tipo_natureza->natureza->descricao }}</span>
                        <span><b>Tipo da Natureza: </b>{{ $acao->tipo_natureza->descricao }}</span>
                        <span><b>Status: </b>
                            @if ($acao->status)
                                {{ $acao->status }}
                            @else
                                Não submetida
                            @endif
                        </span>
                        <span><b>Inicio: </b>{{ date('d/m/Y', strtotime($acao->data_inicio)) }}</span>
                        <span><b>Fim: </b>{{ date('d/m/Y', strtotime($acao->data_fim)) }}</span>
                        @if ($acao->anexo)
                            <span><b>Anexo: </b>
                                <a href="{{ route('anexo.download', ['acao_id' => $acao->id]) }}" title="Baixar Anexo">
                                    <img src="/images/acoes/listView/anexo.svg" alt="Visualizar">
                                </a>
                            </span>
                        @endif
                        @if ($acao->observacao_gestor)
                            <span><b>Observações do Gestor: </b>{{ $acao->observacao_gestor }}</span>
                        @endif
                    </div>

                    @if (count($acao->atividades))
                        <hr>
                        <div class="row justify-content-center">
                            <h5>Atividades</h5>
                            @foreach ($acao->atividades as $atividade)
                                <span><b>Descrição: </b>{{ $atividade->descricao }}</span>
                                <div class="col-10">
                                    <span><b>Integrantes:</b></span>
                                    @foreach ($atividade->participantes as $participante)
                                        <div>
                                            <ul>
                                                <span><b>Nome: </b>{{ $participante->user->name }}</span><br>
                                                <span><b>E-mail: </b>{{ $participante->user->email }}</span><br>
                                                <span><b>Carga Horária:
                                                    </b>{{ $participante->carga_horaria }}</span><br>
                                                <span><b>Inicio:
                                                    </b>{{ date('d/m/Y', strtotime($atividade->data_inicio)) }}</span><br>
                                                <span><b>Fim:
                                                    </b>{{ date('d/m/Y', strtotime($atividade->data_fim)) }}</span><br>
                                            </ul>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    $(document).ready(function(){
        // Exibe o modal ao clicar no link com o atributo data-toggle e data-target correspondentes
        $('[data-toggle="modal"]').click(function(){
            var target_modal = $(this).data('target');
            $(target_modal).modal('show');
        });
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

